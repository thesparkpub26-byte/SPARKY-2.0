<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use App\Models\Notification;
use App\Support\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Rules\NotCommonPassword;

class UserController extends Controller
{
    /** List all users, optionally filter by role */
    public function index(Request $request)
    {
        $query = User::withCount('assignedTasks')
            ->with(['assignedTasks.section', 'articles.section']);

        $this->filterBy($query, $request, ['role' => 'role']);
        // Readers are the public's accounts; only admin / EIC manage them
        if (!in_array($request->user()->role, ['admin', 'eic'], true)) {
            $query->where('role', '!=', User::ROLE_READER);
        }
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        return response()->json($query->orderBy('name')->get());
    }

    /** Get a single user */
    public function show(User $user)
    {
        return response()->json($user->load(['assignedTasks', 'articles']));
    }

    /** Create a new user */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => ['required', 'string', ...NotCommonPassword::rules()],
            'role'       => 'required|in:admin,eic,section_editor,staff_writer,staff_artist,staff_broadcaster,reader',
            'secondary_role' => 'nullable|string|max:255',
            'tertiary_role' => 'nullable|string|max:255|different:secondary_role',
            'program'    => 'nullable|string|max:255',
            'year_section' => 'nullable|string|max:255',
            'is_active'  => 'sometimes|boolean',
            'profile_picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $this->guardAdminAccounts($request, null, $validated['role']);

        if (($validated['tertiary_role'] ?? null) && !in_array($validated['role'], ['section_editor', 'eic'], true)) {
            $validated['tertiary_role'] = null;
        }

        $validated['password'] = Hash::make($validated['password']);
        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = Images::store($request->file('profile_picture'), 'profile_pictures', Images::AVATAR);
        }
        $user = User::create($validated);
        Activity::record($request->user(), 'Added a user', $user);

        return response()->json($user, 201);
    }

    /** Update a user */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'       => 'sometimes|string|max:255',
            'email'      => 'sometimes|email|unique:users,email,' . $user->id,
            'password'   => ['sometimes', 'string', ...NotCommonPassword::rules()],
            'role'       => 'sometimes|in:admin,eic,section_editor,staff_writer,staff_artist,staff_broadcaster,reader',
            'secondary_role' => 'nullable|string|max:255',
            'tertiary_role' => 'nullable|string|max:255|different:secondary_role',
            'program'    => 'nullable|string|max:255',
            'year_section' => 'nullable|string|max:255',
            'is_active'  => 'sometimes|boolean',
            'profile_picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $this->guardAdminAccounts($request, $user, $validated['role'] ?? null);
        $this->guardEditorInChiefSuccession($request, $user, $validated['role'] ?? $user->role, $request->boolean('is_active', $user->is_active));

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $validated['profile_picture'] = Images::store($request->file('profile_picture'), 'profile_pictures', Images::AVATAR);
        }

        $resolvedRole = $validated['role'] ?? $user->role;
        if (($validated['tertiary_role'] ?? null) && !in_array($resolvedRole, ['section_editor', 'eic'], true)) {
            $validated['tertiary_role'] = null;
        }

        $becomesEic = $resolvedRole === User::ROLE_EIC && !$user->isEIC();

        $user->update($validated);
        if ($becomesEic) {
            Notification::create([
                'user_id' => $user->id,
                'title'   => 'You are now the Editor-in-Chief',
                'message' => "{$request->user()->name} made you the Editor-in-Chief of TheSPARK. Sign in again to see your new dashboard.",
                'type'    => Notification::TYPE_GENERAL,
            ]);
        }
        if (isset($validated['password']) || ($validated['is_active'] ?? true) === false) {
            $user->tokens()->delete();
        }
        Activity::record($request->user(), 'Updated a user', $user);

        return response()->json($user);
    }

    /** Delete a user (admin action) */
    public function destroy(User $user)
    {
        $this->guardAdminAccounts(request(), $user, null);
        $this->guardEditorInChiefSuccession(request(), $user, 'deleted', false);

        Activity::record(request()->user(), 'Deleted a user', $user);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.']);
    }

    /**
     * The Editor-in-Chief hands over by promoting the next one; the new Editor-in-Chief then deactivates or
     * re-titles the old one. So nobody steps themselves down, and the site is never left without an active
     * Editor-in-Chief.
     */
    private function guardEditorInChiefSuccession(Request $request, User $target, string $newRole, bool $willBeActive): void
    {
        if (!$target->isEIC() || !$target->is_active || ($newRole === User::ROLE_EIC && $willBeActive)) {
            return; // not an active Editor-in-Chief losing that position
        }

        abort_if(
            $request->user()->is($target) && !$request->user()->isAdmin(),
            403,
            "You can't step down yourself. Promote the next Editor-in-Chief first: they can then change your position or set you inactive."
        );

        abort_if(
            User::where('role', User::ROLE_EIC)->where('is_active', true)->whereKeyNot($target->id)->doesntExist(),
            422,
            'There must always be an active Editor-in-Chief. Promote the next Editor-in-Chief first.'
        );
    }

    /** Only an admin may create, promote, edit or delete admin accounts. */
    private function guardAdminAccounts(Request $request, ?User $target, ?string $newRole): void
    {
        if ($request->user()->isAdmin()) return;

        abort_if(($target && $target->isAdmin()) || $newRole === User::ROLE_ADMIN, 403, 'Only an admin can manage admin accounts.');
    }

    /** Update own profile: name, password, profile picture */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'                  => 'sometimes|string|max:255',
            'current_password'      => 'required_with:password|nullable|current_password:sanctum',
            'password'              => ['sometimes', 'string', ...NotCommonPassword::rules(), 'confirmed'],
            'profile_picture'       => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);
        unset($validated['current_password']);

        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = Images::store($request->file('profile_picture'), 'profile_pictures', Images::AVATAR);
            $validated['profile_picture'] = $path;
        }

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        if (isset($validated['password'])) {
            // Other devices / stolen tokens are signed out; this session stays
            $user->tokens()->where('id', '!=', $request->user()->currentAccessToken()?->id)->delete();
        }
        Activity::record($request->user(), 'Updated profile', $user);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user'    => $user->fresh(),
        ]);
    }

    /** Delete own account permanently */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Only administrators can delete their account. Please contact an admin if you need your account removed.'], 403);
        }

        $request->validate(['password' => 'required|string|current_password:sanctum']);

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
            return response()->json(['message' => 'Could not delete your account. Please try again later.'], 500);
        }
    }
}
