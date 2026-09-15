<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /** List all users, optionally filter by role/section */
    public function index(Request $request)
    {
        $query = User::with('section');

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }
        if ($request->has('section_id')) {
            $query->where('section_id', $request->section_id);
        }
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        return response()->json($query->orderBy('name')->get());
    }

    /** Get a single user */
    public function show(User $user)
    {
        return response()->json($user->load(['section', 'assignedTasks', 'articles']));
    }

    /** Create a new user */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|string|min:8',
            'role'       => 'required|in:admin,eic,section_editor,staff_writer,staff_artist,reader',
            'section_id' => 'nullable|exists:sections,id',
            'bio'        => 'nullable|string',
            'is_active'  => 'sometimes|boolean',
            'profile_picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }
        $user = User::create($validated);
        Activity::record($request->user(), 'Added a user', $user);

        return response()->json($user->load('section'), 201);
    }

    /** Update a user */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'       => 'sometimes|string|max:255',
            'email'      => 'sometimes|email|unique:users,email,' . $user->id,
            'password'   => 'sometimes|string|min:8',
            'role'       => 'sometimes|in:admin,eic,section_editor,staff_writer,staff_artist,reader',
            'section_id' => 'nullable|exists:sections,id',
            'bio'        => 'nullable|string',
            'avatar'     => 'nullable|string',
            'is_active'  => 'sometimes|boolean',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        Activity::record($request->user(), 'Updated a user', $user);

        return response()->json($user->load('section'));
    }

    /** Delete a user (admin action) */
    public function destroy(User $user)
    {
        Activity::record(request()->user(), 'Deleted a user', $user);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.']);
    }

    /** Update own profile: name, password, profile picture */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'                  => 'sometimes|string|max:255',
            'password'              => 'sometimes|string|min:8|confirmed',
            'profile_picture'       => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        Activity::record($request->user(), 'Updated profile', $user);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user'    => $user->fresh()->load('section'),
        ]);
    }

    /** Delete own account permanently */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        try {
            DB::transaction(function () use ($user) {
                $user->tokens()->delete();
                $user->notifications()->delete();
                $user->articles()->delete();
                $user->assignedTasks()->delete();
                $user->createdTasks()->delete();
                $user->delete();
                Activity::record(null, 'Deleted account', null, $user->name);
            });

            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            return response()->json(['message' => 'Account deleted permanently.']);

        } catch (\Exception $e) {
            \Log::error('deleteAccount failed for user '.$user->id.': '.$e->getMessage());
            return response()->json([
                'message' => 'Could not delete account: '.$e->getMessage()
            ], 500);
        }
    }
}
