<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'role'       => 'required|in:admin,eic,section_editor,staff_writer,staff_artist',
            'section_id' => 'nullable|exists:sections,id',
            'bio'        => 'nullable|string',
            'avatar'     => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        return response()->json($user->load('section'), 201);
    }

    /** Update a user */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'       => 'sometimes|string|max:255',
            'email'      => 'sometimes|email|unique:users,email,' . $user->id,
            'password'   => 'sometimes|string|min:8',
            'role'       => 'sometimes|in:admin,eic,section_editor,staff_writer,staff_artist',
            'section_id' => 'nullable|exists:sections,id',
            'bio'        => 'nullable|string',
            'avatar'     => 'nullable|string',
            'is_active'  => 'sometimes|boolean',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user->load('section'));
    }

    /** Delete a user */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.']);
    }
}
