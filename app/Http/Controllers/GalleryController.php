<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        return response()->json(GalleryPhoto::with('uploader')->latest()->get());
    }

    public function store(Request $request)
    {
        if (!in_array($request->user()->role, ['eic', 'admin'])) {
            return response()->json(['message' => 'Not authorized to upload gallery photos.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $path = $request->file('photo')->store('gallery', 'public');

        $photo = GalleryPhoto::create([
            'title'       => $validated['title'],
            'image_path'  => $path,
            'uploaded_by' => $request->user()->id,
        ]);

        Activity::record($request->user(), 'Uploaded a gallery photo', $photo);

        return response()->json($photo->load('uploader'), 201);
    }

    public function update(Request $request, GalleryPhoto $photo)
    {
        if (!in_array($request->user()->role, ['eic', 'admin'])) {
            return response()->json(['message' => 'Not authorized to edit gallery photos.'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($request->hasFile('photo')) {
            if ($photo->image_path) {
                Storage::disk('public')->delete($photo->image_path);
            }
            $validated['image_path'] = $request->file('photo')->store('gallery', 'public');
            unset($validated['photo']);
        }

        $photo->update($validated);
        Activity::record($request->user(), 'Updated a gallery photo', $photo);

        return response()->json($photo->load('uploader'));
    }

    public function destroy(Request $request, GalleryPhoto $photo)
    {
        if (!in_array($request->user()->role, ['eic', 'admin'])) {
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
