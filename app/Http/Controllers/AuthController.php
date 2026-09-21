<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'You are no longer a part of the publication, but you can still use your email to create a reader account with the sign up option.'], 403);
        }

        $token = $user->createToken('sparky-token')->plainTextToken;
        Activity::record($user, 'Logged in');

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
        Activity::record($request->user(), 'Logged out');
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
