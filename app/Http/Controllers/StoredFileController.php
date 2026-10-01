<?php

namespace App\Http\Controllers;

use App\Models\StoredFile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Serves uploaded photos, avatars and issue PDFs from the database at /storage/{path}. */
class StoredFileController extends Controller
{
    /** Streams an uploaded file (photo, PDF) kept in the database, with caching headers. */
    public function show(Request $request, string $path)
    {
        $file = StoredFile::where('path', $path)->first(['id', 'path', 'mime_type', 'size', 'sha1']);
        abort_unless($file, 404);

        $response = new StreamedResponse(function () use ($file) {
            foreach ($file->chunks() as $chunk) {
                echo $chunk;
            }
        }, 200, [
            'Content-Type'        => $file->mime_type,
            'Content-Length'      => $file->size,
            'Content-Disposition' => 'inline',
            // Every upload gets a new random name and is never changed afterwards, so it can be kept for good
            'Cache-Control'       => 'public, max-age=31536000, immutable',
        ]);
        $response->setEtag($file->sha1);

        // The browser already has this exact file: send nothing
        $response->isNotModified($request);

        return $response;
    }
}
