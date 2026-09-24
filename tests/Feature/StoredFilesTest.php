<?php

namespace Tests\Feature;

use App\Models\PublishedIssue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\MakesData;
use Tests\TestCase;

/** Uploaded photos and PDFs are kept in the database and served from /storage/{path}. */
class StoredFilesTest extends TestCase
{
    use MakesData, RefreshDatabase;

    private function body($response): string
    {
        ob_start();
        $response->sendContent();

        return ob_get_clean();
    }

    public function test_a_large_file_is_stored_in_pieces_and_served_back_unchanged(): void
    {
        $bytes = "%PDF-1.4\n" . random_bytes(700 * 1024) . "\n%%EOF";   // spans three 256 KB chunks

        Storage::disk('public')->put('published-issues/big.pdf', $bytes);

        $this->assertSame(3, DB::table('stored_file_chunks')->count());
        $this->assertDatabaseHas('stored_files', ['path' => 'published-issues/big.pdf', 'mime_type' => 'application/pdf', 'size' => strlen($bytes), 'sha1' => sha1($bytes)]);

        $response = $this->get('/storage/published-issues/big.pdf')->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertSame($bytes, $this->body($response->baseResponse));
        $this->assertSame((string) strlen($bytes), $response->headers->get('Content-Length'));
        $this->assertTrue(Storage::disk('public')->exists('published-issues/big.pdf'));
        $this->assertSame($bytes, Storage::disk('public')->get('published-issues/big.pdf'));
    }

    public function test_a_browser_that_already_has_the_file_gets_a_304(): void
    {
        Storage::disk('public')->put('gallery/a.txt', 'hello');
        $etag = $this->get('/storage/gallery/a.txt')->headers->get('ETag');

        $this->assertNotEmpty($etag);
        $this->get('/storage/gallery/a.txt', ['If-None-Match' => $etag])->assertStatus(304);
    }

    public function test_missing_files_and_path_tricks_are_404(): void
    {
        $this->get('/storage/nope/none.jpg')->assertNotFound();
        $this->get('/storage/..%2F.env')->assertNotFound();
        $this->get('/storage/gallery/../../.env')->assertNotFound();
    }

    public function test_a_published_issue_pdf_is_uploaded_replaced_and_deleted_in_the_database(): void
    {
        Sanctum::actingAs($this->makeUser('eic'));
        $pdf = fn (string $marker) => UploadedFile::fake()->createWithContent('issue.pdf', "%PDF-1.4\n{$marker}\n%%EOF");

        $created = $this->postJson('/api/published-issues', ['title' => 'Issue 1', 'pdf' => $pdf('first')])->assertCreated();
        $issue = PublishedIssue::findOrFail($created->json('id'));

        $this->assertStringStartsWith('published-issues/', $issue->pdf_path);
        $this->assertSame('/storage/' . $issue->pdf_path, parse_url(Storage::disk('public')->url($issue->pdf_path), PHP_URL_PATH));
        $this->assertStringContainsString('first', $this->body($this->get('/storage/' . $issue->pdf_path)->baseResponse));

        // replacing the PDF removes the old file's rows, so nothing piles up
        $old = $issue->pdf_path;
        $this->postJson("/api/published-issues/{$issue->id}", ['pdf' => $pdf('second')])->assertOk();
        $issue->refresh();
        $this->get('/storage/' . $old)->assertNotFound();
        $this->assertStringContainsString('second', $this->body($this->get('/storage/' . $issue->pdf_path)->baseResponse));
        $this->assertSame(1, DB::table('stored_files')->count());

        $this->deleteJson("/api/published-issues/{$issue->id}")->assertOk();
        $this->assertSame(0, DB::table('stored_files')->count());
        $this->assertSame(0, DB::table('stored_file_chunks')->count());
    }

    public function test_article_photos_are_uploaded_into_the_database(): void
    {
        Sanctum::actingAs($this->makeUser('staff_writer'));

        $response = $this->post('/api/articles/upload-media', [
            'files' => [UploadedFile::fake()->image('a.jpg', 40, 40), UploadedFile::fake()->image('b.png', 40, 40)],
        ], ['Accept' => 'application/json'])->assertCreated();

        $this->assertCount(2, $response->json('urls'));
        foreach ($response->json('urls') as $url) {
            $this->assertStringStartsWith('/storage/article-media/', $url);
            $this->get($url)->assertOk();
        }
        $this->assertSame(2, DB::table('stored_files')->count());
    }
}
