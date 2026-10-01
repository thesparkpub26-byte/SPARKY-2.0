<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route guard: ->middleware('role:admin,eic') lets only those roles through, and
 * ->middleware('role:staff') lets any staff role through (readers are turned away).
 * Deactivated accounts are refused even if they still hold an old token.
 */
class EnsureRole
{
    /** Allows the request only if the signed-in, active user has one of the roles the route asks for. */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_active) {
            return response()->json(['message' => 'Your account cannot use this feature.'], 403);
        }

        $allowed = in_array('staff', $roles, true) ? $user->isStaff() : in_array($user->role, $roles, true);
        if (!$allowed) {
            return response()->json(['message' => 'You are not allowed to do this.'], 403);
        }

        return $next($request);
    }
}
