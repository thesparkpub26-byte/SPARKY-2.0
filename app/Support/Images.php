<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

/**
 * Photos are kept in the database, and a phone photo is 4000+ pixels wide and several MB while the site
 * shows it at a fraction of that. Uploads larger than a limit are scaled down (never up) before they are
 * stored; smaller ones, GIFs and anything this cannot handle safely are stored exactly as uploaded.
 */
class Images
{
    /** Longest side, in pixels. A profile picture is shown at 30-200 px. */
    public const AVATAR = 512;

    /** Article photos and the gallery: still plenty for a full-width photo on a large screen. */
    public const PHOTO = 2400;

    /** Scales the upload down if needed and stores it on the public disk. Returns the stored path. */
    public static function store(UploadedFile $file, string $directory, int $maxSide): string
    {
        self::shrink((string) $file->getRealPath(), $maxSide);

        return $file->store($directory, 'public');
    }

    private static function shrink(string $path, int $maxSide): void
    {
        $info = @getimagesize($path);
        if (!$info || max($info[0], $info[1]) <= $maxSide) {
            return;
        }
        [$width, $height, $type] = $info;

        // GIFs may be animated, and re-encoding would freeze them
        if (!in_array($type, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            return;
        }

        // Decoding needs roughly 5 bytes per pixel; leave the photo alone rather than run out of memory
        $limit = self::memoryLimit();
        if ($limit > 0 && memory_get_usage() + $width * $height * 5 > $limit * 0.8) {
            return;
        }

        // Re-encoding drops the camera's "rotate me" note, so it must be applied to the pixels first. If that
        // note can't be read (or is a mirror flip), keep the original: browsers still show it the right way up.
        $rotate = 0;
        if ($type === IMAGETYPE_JPEG) {
            if (!function_exists('exif_read_data')) {
                return;
            }
            $orientation = (int) (@exif_read_data($path)['Orientation'] ?? 1);
            if (in_array($orientation, [2, 4, 5, 7], true)) {
                return;
            }
            $rotate = [3 => 180, 6 => -90, 8 => 90][$orientation] ?? 0;
        }

        try {
            $image = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
                IMAGETYPE_PNG  => @imagecreatefrompng($path),
                IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            };
            if (!$image) {
                return;
            }
            if ($rotate) {
                $image = imagerotate($image, $rotate, 0) ?: $image;
            }

            // (after a quarter turn the sides swap, so measure the image as it is now)
            $width  = imagesx($image);
            $height = imagesy($image);
            $ratio  = $maxSide / max($width, $height);
            $newWidth  = max(1, (int) round($width * $ratio));
            $newHeight = max(1, (int) round($height * $ratio));

            // Keep transparency (PNG and WebP avatars often have it)
            $scaled = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($scaled, false);
            imagesavealpha($scaled, true);
            imagefill($scaled, 0, 0, imagecolorallocatealpha($scaled, 0, 0, 0, 127));
            imagecopyresampled($scaled, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            match ($type) {
                IMAGETYPE_JPEG => imagejpeg($scaled, $path, 85),
                IMAGETYPE_PNG  => imagepng($scaled, $path, 6),
                IMAGETYPE_WEBP => imagewebp($scaled, $path, 85),
            };
        } catch (\Throwable) {
            // The original file is still there; store it as it is
        }
    }

    /** PHP's memory limit in bytes, or 0 for "no limit". */
    private static function memoryLimit(): int
    {
        $value = trim((string) ini_get('memory_limit'));
        if ($value === '' || $value === '-1') {
            return 0;
        }
        $number = (int) $value;

        return match (strtolower(substr($value, -1))) {
            'g'     => $number * 1073741824,
            'm'     => $number * 1048576,
            'k'     => $number * 1024,
            default => $number,
        };
    }
}
