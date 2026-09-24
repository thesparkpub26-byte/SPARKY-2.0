<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\GalleryPhoto;
use App\Models\User;
use App\Support\Images;
use App\Support\PublicCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    /** EIC / admin, or the Section Editor who is the Art Editor, can upload, edit and delete gallery photos. */
    private function canManage(User $user): bool
    {
        if (in_array($user->role, ['eic', 'admin'])) return true;

        return $user->role === 'section_editor'
            && stripos(($user->secondary_role ?? '') . ' ' . ($user->tertiary_role ?? ''), 'art editor') !== false;
    }

    /** Everyone who can be credited on a photo: the staff artists and the Art Editor. */
    private function artistsQuery()
    {
        return User::where(fn ($q) => $q->where('is_active', true)->orWhereNull('is_active'))
            ->where(function ($q) {
                $q->where('role', 'staff_artist')
                  ->orWhere(fn ($q) => $q->where('role', 'section_editor')
                      ->where(fn ($q) => $q->where('secondary_role', 'like', '%Art Editor%')
                                            ->orWhere('tertiary_role', 'like', '%Art Editor%')));
            });
    }

    /** Author choices for the upload / edit photo forms. */
    public function artists(Request $request)
    {
        if (!$this->canManage($request->user())) {
            return response()->json(['message' => 'Not authorized.'], 403);
        }

        return response()->json(
            $this->artistsQuery()->orderBy('name')->get(['id', 'name', 'role', 'secondary_role', 'tertiary_role', 'profile_picture'])
        );
    }

    public function index()
    {
        return response()->json(GalleryPhoto::with(['uploader', 'artist'])->latest()->get());
    }

    /**
     * Public: gallery photos for the reader site, newest first. Optional ?limit= (home page uses 3);
     * with ?page= the list is paginated (12 a page) and wrapped with the paging info.
     */
    public function latest(Request $request)
    {
        return response()->json(PublicCache::remember($request, 'gallery', ['limit', 'page'], fn () => $this->latestList($request)));
    }

    private function latestList(Request $request): array
    {
        $query = GalleryPhoto::with('artist:id,name')->latest('created_at')->latest('id');
        $toPhoto = fn (GalleryPhoto $p) => [
            'id'     => $p->id,
            'title'  => $p->title,
            'image'  => $p->image_path ? '/storage/' . $p->image_path : null,
            'artist' => $p->artist?->name,
            'date'   => $p->created_at?->toIso8601String(),
        ];

        if ($request->filled('page')) {
            $page = $query->paginate(12);

            return [
                'data'         => $page->getCollection()->map($toPhoto)->values()->all(),
                'current_page' => $page->currentPage(),
                'last_page'    => $page->lastPage(),
                'total'        => $page->total(),
            ];
        }

        if ($request->filled('limit')) {
            $query->limit(max(1, min(50, $request->integer('limit'))));
        }

        return $query->get()->map($toPhoto)->all();
    }

    /** Public: one photo, so a search result can open it in the gallery lightbox. */
    public function publicShow(GalleryPhoto $photo)
    {
        $photo->load('artist:id,name');

        return response()->json([
            'id'     => $photo->id,
            'title'  => $photo->title,
            'image'  => $photo->image_path ? '/storage/' . $photo->image_path : null,
            'artist' => $photo->artist?->name,
            'date'   => $photo->created_at?->toIso8601String(),
        ]);
    }

    public function store(Request $request)
    {
        if (!$this->canManage($request->user())) {
            return response()->json(['message' => 'Not authorized to upload gallery photos.'], 403);
        }

        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'photo'     => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'artist_id' => ['required', 'integer', Rule::in($this->artistsQuery()->pluck('id')->all())],
        ], ['artist_id.in' => 'Please pick an artist or the Art Editor as the author.']);

        $path = Images::store($request->file('photo'), 'gallery', Images::PHOTO);

        $photo = GalleryPhoto::create([
            'title'       => $validated['title'],
            'image_path'  => $path,
            'uploaded_by' => $request->user()->id,
            'artist_id'   => $validated['artist_id'],
        ]);

        Activity::record($request->user(), 'Uploaded a gallery photo', $photo);

        return response()->json($photo->load(['uploader', 'artist']), 201);
    }

    public function update(Request $request, GalleryPhoto $photo)
    {
        if (!$this->canManage($request->user())) {
            return response()->json(['message' => 'Not authorized to edit gallery photos.'], 403);
        }

        // Keeping the current author is always fine, even if they are no longer active
        $allowedArtists = $this->artistsQuery()->pluck('id')->push($photo->artist_id)->filter()->values()->all();

        $validated = $request->validate([
            'title'     => 'sometimes|required|string|max:255',
            'photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'artist_id' => ['sometimes', 'required', 'integer', Rule::in($allowedArtists)],
        ], ['artist_id.in' => 'Please pick an artist or the Art Editor as the author.']);

        if ($request->hasFile('photo')) {
            if ($photo->image_path) {
                Storage::disk('public')->delete($photo->image_path);
            }
            $validated['image_path'] = Images::store($request->file('photo'), 'gallery', Images::PHOTO);
            unset($validated['photo']);
        }

        $photo->update($validated);
        Activity::record($request->user(), 'Updated a gallery photo', $photo);

        return response()->json($photo->load(['uploader', 'artist']));
    }

    public function destroy(Request $request, GalleryPhoto $photo)
    {
        if (!$this->canManage($request->user())) {
            return response()->json(['message' => 'Not authorized to delete gallery photos.'], 403);
        }

        Activity::record($request->user(), 'Deleted a gallery photo', $photo);

        if ($photo->image_path) {
            Storage::disk('public')->delete($photo->image_path);
        }
        $photo->delete();

        return response()->json(['message' => 'Photo deleted successfully.']);
    }
}
