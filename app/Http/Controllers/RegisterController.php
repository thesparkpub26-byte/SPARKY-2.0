<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * Step 1 – Validate signup details and send OTP email.
     * The account is NOT created yet; it's held in otp_verifications.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

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
        Mail::to($request->email)->send(new OtpMail($otp, $request->name));

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

        if ($record->otp !== $request->otp) {
            return response()->json(['message' => 'Incorrect verification code. Please try again.'], 422);
        }

        // OTP is valid — create the real user account
        $user = User::create([
            'name'     => $record->name,
            'email'    => $record->email,
            'password' => $record->password,
            'role'     => 'reader', // Public signups are readers; Admin assigns staff roles
        ]);

        // Clean up
        $record->delete();

        // Issue a Sanctum token and log them in
        $token = $user->createToken('sparky-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user->load('section'),
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
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($record->email)->send(new OtpMail($otp, $record->name));

        return response()->json(['message' => 'A new verification code has been sent.']);
    }
}
