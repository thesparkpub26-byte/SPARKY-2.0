<?php

namespace Tests\Feature;

use App\Support\Images;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\MakesData;
use Tests\TestCase;

/** Big photos are scaled down before they are stored in the database; everything else is left alone. */
class ImageUploadTest extends TestCase
{
    use MakesData, RefreshDatabase;

    private function storedSize(string $path): array
    {
        $info = getimagesizefromstring(Storage::disk('public')->get($path));

        return [$info[0], $info[1]];
    }

    public function test_a_large_profile_picture_is_scaled_down_and_keeps_its_shape(): void
    {
        $user = $this->makeUser('reader');
        Sanctum::actingAs($user);

        $this->post('/api/profile', ['profile_picture' => UploadedFile::fake()->image('me.jpg', 3000, 2000)], ['Accept' => 'application/json'])->assertOk();

        [$width, $height] = $this->storedSize($user->fresh()->profile_picture);
        $this->assertSame(Images::AVATAR, $width);
        $this->assertSame(341, $height);   // 3:2 stays 3:2
    }

    public function test_a_portrait_photo_is_limited_by_its_height(): void
    {
        Sanctum::actingAs($this->makeUser('staff_writer'));

        $url = $this->post('/api/articles/upload-media', ['files' => [UploadedFile::fake()->image('tall.jpg', 2000, 4000)]], ['Accept' => 'application/json'])
            ->assertCreated()->json('urls.0');

        [$width, $height] = $this->storedSize(substr($url, strlen('/storage/')));
        $this->assertSame(Images::PHOTO, $height);
        $this->assertSame(1200, $width);
    }

    public function test_pictures_within_the_limit_are_stored_exactly_as_uploaded(): void
    {
        Sanctum::actingAs($this->makeUser('staff_writer'));
        $file = UploadedFile::fake()->image('small.jpg', 800, 600);
        $original = file_get_contents($file->getRealPath());

        $url = $this->post('/api/articles/upload-media', ['files' => [$file]], ['Accept' => 'application/json'])->assertCreated()->json('urls.0');

        $this->assertSame($original, Storage::disk('public')->get(substr($url, strlen('/storage/'))));
    }

    public function test_a_transparent_png_stays_transparent_after_scaling(): void
    {
        $user = $this->makeUser('reader');
        Sanctum::actingAs($user);

        $png = imagecreatetruecolor(1600, 1600);
        imagealphablending($png, false);
        imagesavealpha($png, true);
        imagefill($png, 0, 0, imagecolorallocatealpha($png, 255, 0, 0, 127));   // fully transparent
        $path = tempnam(sys_get_temp_dir(), 'png');
        imagepng($png, $path);

        $this->post('/api/profile', ['profile_picture' => new UploadedFile($path, 'logo.png', 'image/png', null, true)], ['Accept' => 'application/json'])->assertOk();

        $stored = imagecreatefromstring(Storage::disk('public')->get($user->fresh()->profile_picture));
        $this->assertSame(Images::AVATAR, imagesx($stored));
        $this->assertSame(127, (imagecolorat($stored, 10, 10) >> 24) & 0x7F, 'the corner is still fully transparent');
    }

    public function test_an_oversized_gif_is_left_alone_because_it_may_be_animated(): void
    {
        Sanctum::actingAs($this->makeUser('staff_writer'));
        $canvas = imagecreatetruecolor(3000, 2000);
        $path = tempnam(sys_get_temp_dir(), 'gif');
        imagegif($canvas, $path);
        $original = file_get_contents($path);

        $url = $this->post('/api/articles/upload-media', ['files' => [new UploadedFile($path, 'big.gif', 'image/gif', null, true)]], ['Accept' => 'application/json'])
            ->assertCreated()->json('urls.0');

        $this->assertStringEndsWith('.gif', $url);
        $this->assertSame($original, Storage::disk('public')->get(substr($url, strlen('/storage/'))));
    }

    public function test_files_of_any_length_come_back_whole(): void
    {
        $chunk = 262144;
        // empty, one byte, just under / exactly on / just over a chunk boundary, and a long file that needs several reads
        foreach ([0, 1, $chunk - 1, $chunk, $chunk + 1, 2 * $chunk, 9 * $chunk + 17] as $size) {
            $bytes = $size ? random_bytes($size) : '';
            Storage::disk('public')->put("t/{$size}.bin", $bytes);

            $response = $this->get("/storage/t/{$size}.bin")->assertOk();
            ob_start();
            $response->baseResponse->sendContent();
            $this->assertSame($bytes, ob_get_clean(), "a file of {$size} bytes");
        }
        $this->assertSame(7, DB::table('stored_files')->count());
    }
}
