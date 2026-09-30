<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Rules\NotCommonPassword;

class RegisterController extends Controller
{
    /**
     * Step 1 – Validate signup details and send OTP email.
     * The account is NOT created yet; it's held in otp_verifications.
     *
     * While the email code is switched off (config security.signup_otp), the account is created right here instead.
     */
    public function sendOtp(Request $request)
    {
        // Check if an ACTIVE user already has this email
        $activeUser = User::where('email', $request->email)->where('is_active', true)->first();
        if ($activeUser) {
            return response()->json([
                'message' => 'The email has already been taken.',
                'errors'  => ['email' => ['The email has already been taken.']],
            ], 422);
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => ['required', 'string', ...NotCommonPassword::rules(), 'confirmed'],
        ]);

        if (!config('security.signup_otp')) {
            return $this->registerWithoutCode($request->name, $request->email, $request->password);
        }

        // Remove any previous pending OTPs for this email
        OtpVerification::where('email', $request->email)->delete();

        // Generate a cryptographically secure 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store the pending registration
        OtpVerification::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP email
        if ($failure = $this->sendCode($request->email, new OtpMail($otp, $request->name))) {
            OtpVerification::where('email', $request->email)->delete(); // nothing to verify: let them retry cleanly
            return $failure;
        }

        return response()->json([
            'message' => 'Verification code sent to ' . $request->email,
        ]);
    }

    /**
     * Step 2 – Verify OTP, create the user account, and return a token.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $record = OtpVerification::where('email', $request->email)->latest()->first();

        if (!$record) {
            return response()->json(['message' => 'No pending verification found. Please sign up again.'], 404);
        }

        if ($record->isExpired()) {
            $record->delete();
            return response()->json(['message' => 'Verification code has expired. Please sign up again.'], 422);
        }

        if ($record->attempts >= 5) {
            $record->delete();
            return response()->json(['message' => 'Too many incorrect attempts. Please sign up again to get a new code.'], 422);
        }

        if (!hash_equals((string) $record->otp, (string) $request->otp)) {
            $record->increment('attempts');
            return response()->json(['message' => 'Incorrect verification code. Please try again.'], 422);
        }

        // OTP is valid — create or reactivate user as a reader
        $existingUser = User::where('email', $record->email)->first();
        if ($existingUser) {
            $existingUser->update([
                'name'           => $record->name,
                'password'       => $record->password,
                'role'           => 'reader',
                'secondary_role' => null,
                'is_active'      => true,
            ]);
            $user = $existingUser;
        } else {
            $user = User::create([
                'name'     => $record->name,
                'email'    => $record->email,
                'password' => $record->password,
                'role'     => 'reader',
                'is_active' => true,
            ]);
        }

        // Clean up
        $record->delete();

        // Issue a Sanctum token and log them in
        $token = $user->createToken('sparky-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ], 201);
    }

    /**
     * Resend OTP – generates a fresh code for an existing pending registration.
     */
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $record = OtpVerification::where('email', $request->email)->latest()->first();

        if (!$record) {
            return response()->json(['message' => 'No pending registration found for this email.'], 404);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $record->update([
            'otp'        => $otp,
            'attempts'   => 0,
            'expires_at' => now()->addMinutes(10),
        ]);

        if ($failure = $this->sendCode($record->email, new OtpMail($otp, $record->name))) {
            return $failure;
        }

        return response()->json(['message' => 'A new verification code has been sent.']);
    }

    /**
     * Sign-up with the email code switched off: create the reader account straight away and log them in.
     * Nothing proves the email address here, so an existing deactivated account is never reactivated this way
     * (that would let anyone take over a deactivated account just by knowing its email).
     */
    private function registerWithoutCode(string $name, string $email, string $password)
    {
        if (User::where('email', $email)->exists()) {
            return response()->json([
                'message' => 'This email belongs to an account that is deactivated. Please contact an administrator.',
                'errors'  => ['email' => ['This email belongs to an account that is deactivated. Please contact an administrator.']],
            ], 422);
        }

        $user = User::create([
            'name'      => $name,
            'email'     => $email,
            'password'  => Hash::make($password),
            'role'      => 'reader',
            'is_active' => true,
        ]);

        return response()->json([
            'token' => $user->createToken('sparky-token')->plainTextToken,
            'user'  => $user,
        ], 201);
    }

    /** Sends the code; returns an error response (and logs why) when the mail server can't be reached, null on success. */
    private function sendCode(string $email, OtpMail $mail)
    {
        try {
            Mail::to($email)->send($mail);
        } catch (\Throwable $e) {
            Log::error('Sign-up verification email failed: ' . $e->getMessage());

            return response()->json([
                'message' => "We couldn't send the verification code right now. Please try again in a few minutes.",
            ], 503);
        }

        return null;
    }
}
