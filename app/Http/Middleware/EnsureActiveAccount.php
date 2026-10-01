<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Any signed-in request from a deactivated account is refused, even if the token is still valid. */
class EnsureActiveAccount
{
    /** Blocks requests from deactivated accounts and signs them out by deleting their token. */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && !$user->is_active) {
            $user->currentAccessToken()?->delete();

            return response()->json(['message' => 'This account is no longer active.'], 403);
        }

        return $next($request);
    }
}
