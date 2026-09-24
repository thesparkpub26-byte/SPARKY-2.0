<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Activity;
use App\Models\PasswordResetCode;
use App\Models\User;
use App\Rules\NotCommonPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Forgot password, in three steps: email a 6-digit code, verify it for a reset token,
 * then set the new password with that token.
 */
class PasswordResetController extends Controller
{
    private const CODE_MINUTES  = 10;
    private const TOKEN_MINUTES = 15;
    private const MAX_ATTEMPTS  = 5;

    /** Step 1. Always answers the same way, so it can't be used to find out who has an account. */
    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);
        $email = strtolower(trim($request->email));

        $user = User::whereRaw('LOWER(email) = ?', [$email])->where('is_active', true)->first();
        if ($user) {
            PasswordResetCode::where('email', $user->email)->delete();

            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            PasswordResetCode::create([
                'email'      => $user->email,
                'code_hash'  => PasswordResetCode::hash($code),
                'expires_at' => now()->addMinutes(self::CODE_MINUTES),
            ]);

            try {
                Mail::to($user->email)->send(new OtpMail($code, $user->name, 'reset'));
            } catch (\Throwable $e) {
                Log::error('Password reset email failed for user ' . $user->id . ': ' . $e->getMessage());
            }
        }

        return response()->json(['message' => 'If that email belongs to an account, a verification code is on its way.']);
    }

    /** Step 2. A correct code is exchanged for a one-time reset token. */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'otp'   => 'required|string|size:6',
        ]);
        $email = strtolower(trim($request->email));

        $record = PasswordResetCode::whereRaw('LOWER(email) = ?', [$email])->whereNotNull('code_hash')->latest('id')->first();
        if (!$record || now()->isAfter($record->expires_at)) {
            $record?->delete();
            return response()->json(['message' => 'This code has expired. Please request a new one.'], 422);
        }

        if ($record->attempts >= self::MAX_ATTEMPTS) {
            $record->delete();
            return response()->json(['message' => 'Too many incorrect attempts. Please request a new code.'], 422);
        }

        $record->increment('attempts');
        if (!hash_equals($record->code_hash, PasswordResetCode::hash($request->otp))) {
            return response()->json(['message' => 'Incorrect verification code. Please try again.'], 422);
        }

        $token = Str::random(64);
        $record->update([
            'code_hash'        => null, // a code works once
            'reset_token_hash' => PasswordResetCode::hash($token),
            'reset_expires_at' => now()->addMinutes(self::TOKEN_MINUTES),
        ]);

        return response()->json(['reset_token' => $token]);
    }

    /** Step 3. Sets the new password and signs the account out everywhere. */
    public function reset(Request $request)
    {
        $request->validate([
            'email'       => 'required|email|max:255',
            'reset_token' => 'required|string|size:64',
            'password'    => ['required', 'string', ...NotCommonPassword::rules(), 'confirmed'],
        ]);
        $email = strtolower(trim($request->email));

        $record = PasswordResetCode::whereRaw('LOWER(email) = ?', [$email])->whereNotNull('reset_token_hash')->latest('id')->first();
        $valid = $record
            && now()->isBefore($record->reset_expires_at)
            && hash_equals($record->reset_token_hash, PasswordResetCode::hash($request->reset_token));
        $user = $valid ? User::where('email', $record->email)->where('is_active', true)->first() : null;

        if (!$user) {
            return response()->json(['message' => 'This reset link has expired. Please start again.'], 422);
        }

        $user->update(['password' => $request->password]);
        $user->tokens()->delete();
        PasswordResetCode::where('email', $user->email)->delete();
        Activity::record($user, 'Reset their password');

        return response()->json(['message' => 'Your password has been updated. You can sign in now.']);
    }
}
