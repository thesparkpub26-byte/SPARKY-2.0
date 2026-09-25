<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login and return user data + token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Five wrong passwords for one email (from one address) pause sign-in for 15 minutes
        $attemptKey = 'login:' . strtolower($request->email) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($attemptKey, 5)) {
            $minutes = (int) ceil(RateLimiter::availableIn($attemptKey) / 60);
            Log::warning('Sign-in blocked after repeated failures', ['email' => strtolower($request->email), 'ip' => $request->ip()]);
            return response()->json(['message' => "Too many failed sign-in attempts. Please try again in {$minutes} minute" . ($minutes === 1 ? '' : 's') . '.'], 429);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($attemptKey, 900);
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        RateLimiter::clear($attemptKey);

        if (!$user->is_active) {
            return response()->json(['message' => 'You are no longer a part of the publication, but you can still use your email to create a reader account with the sign up option.'], 403);
        }

        $token = $user->createToken('sparky-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }

    /**
     * Logout and revoke the current token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    /**
     * Return the authenticated user.
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
