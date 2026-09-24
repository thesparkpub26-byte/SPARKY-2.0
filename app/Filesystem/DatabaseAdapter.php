<?php

namespace App\Filesystem;

use App\Models\StoredFile;
use League\Flysystem\Config;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToRetrieveMetadata;
use Illuminate\Support\Facades\DB;
use PDO;

/**
 * A Laravel storage disk that keeps files in the database (stored_files + stored_file_chunks) instead of
 * on the server's disk. `$request->file('x')->store('dir', 'public')`, `Storage::disk('public')->delete()`
 * and `->url()` all work as before; the files are served by StoredFileController at /storage/{path}.
 */
class DatabaseAdapter implements FilesystemAdapter
{
    /** Small enough for MySQL's default 1 MB packet limit. */
    public const CHUNK_SIZE = 262144;

    public function __construct(private string $urlPrefix = '/storage')
    {
    }

    /** Called by Storage::url() */
    public function getUrl(string $path): string
    {
        return rtrim($this->urlPrefix, '/') . '/' . ltrim($path, '/');
    }

    public function fileExists(string $path): bool
    {
        return StoredFile::where('path', $path)->exists();
    }

    public function directoryExists(string $path): bool
    {
        return StoredFile::where('path', 'like', $this->prefixPattern($path))->exists();
    }

    public function write(string $path, string $contents, Config $config): void
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $contents);
        rewind($stream);
        $this->writeStream($path, $stream, $config);
        fclose($stream);
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        DB::transaction(function () use ($path, $contents) {
            $this->delete($path);

            $chunk = (string) stream_get_contents($contents, self::CHUNK_SIZE);
            $mime = (new \finfo(FILEINFO_MIME_TYPE))->buffer($chunk) ?: 'application/octet-stream';
            $file = StoredFile::create(['path' => $path, 'mime_type' => $mime, 'size' => 0, 'sha1' => '']);

            $hash = hash_init('sha1');
            $size = 0;
            for ($seq = 0; $chunk !== ''; $seq++) {
                $this->insertChunk($file->id, $seq, $chunk);
                hash_update($hash, $chunk);
                $size += strlen($chunk);
                $chunk = (string) stream_get_contents($contents, self::CHUNK_SIZE);
            }

            $file->update(['size' => $size, 'sha1' => hash_final($hash)]);
        });
    }

    public function read(string $path): string
    {
        return stream_get_contents($this->readStream($path));
    }

    public function readStream(string $path)
    {
        $file = StoredFile::where('path', $path)->first() ?? throw UnableToReadFile::fromLocation($path, 'File not found.');

        $stream = fopen('php://temp', 'r+');
        foreach ($file->chunks() as $chunk) {
            fwrite($stream, $chunk);
        }
        rewind($stream);

        return $stream;
    }

    public function delete(string $path): void
    {
        $this->deleteWhere(StoredFile::where('path', $path));
    }

    public function deleteDirectory(string $path): void
    {
        $this->deleteWhere(StoredFile::where('path', 'like', $this->prefixPattern($path)));
    }

    public function createDirectory(string $path, Config $config): void
    {
        // Directories are only a prefix of the path; nothing to create
    }

    public function setVisibility(string $path, string $visibility): void
    {
        // Every upload is public (the same as the old "public" disk)
    }

    public function visibility(string $path): FileAttributes
    {
        return new FileAttributes($path, null, 'public');
    }

    public function mimeType(string $path): FileAttributes
    {
        return new FileAttributes($path, null, null, null, $this->find($path)->mime_type);
    }

    public function lastModified(string $path): FileAttributes
    {
        return new FileAttributes($path, null, null, $this->find($path)->updated_at->getTimestamp());
    }

    public function fileSize(string $path): FileAttributes
    {
        return new FileAttributes($path, (int) $this->find($path)->size);
    }

    public function listContents(string $path, bool $deep): iterable
    {
        $prefix = trim($path, '/') === '' ? '' : trim($path, '/') . '/';
        $directories = [];

        foreach (StoredFile::where('path', 'like', $this->prefixPattern($path))->orderBy('path')->get() as $file) {
            $rest = substr($file->path, strlen($prefix));
            if (!$deep && str_contains($rest, '/')) {
                $directories[$prefix . explode('/', $rest)[0]] = true;
                continue;
            }
            yield new FileAttributes($file->path, (int) $file->size, 'public', $file->updated_at->getTimestamp(), $file->mime_type);
        }

        foreach (array_keys($directories) as $directory) {
            yield new DirectoryAttributes($directory);
        }
    }

    public function move(string $source, string $destination, Config $config): void
    {
        $file = StoredFile::where('path', $source)->first() ?? throw UnableToMoveFile::fromLocationTo($source, $destination);

        DB::transaction(function () use ($file, $destination) {
            $this->delete($destination);
            $file->update(['path' => $destination]);
        });
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        if (!$this->fileExists($source)) {
            throw UnableToCopyFile::fromLocationTo($source, $destination);
        }

        $stream = $this->readStream($source);
        $this->writeStream($destination, $stream, $config);
        fclose($stream);
    }

    private function find(string $path): StoredFile
    {
        return StoredFile::where('path', $path)->first() ?? throw UnableToRetrieveMetadata::create($path, 'metadata', 'File not found.');
    }

    private function deleteWhere($query): void
    {
        foreach ($query->pluck('id') as $id) {
            DB::table('stored_file_chunks')->where('stored_file_id', $id)->delete();
            StoredFile::whereKey($id)->delete();
        }
    }

    /** LIKE pattern for everything below a folder, with the characters LIKE treats specially escaped. */
    private function prefixPattern(string $path): string
    {
        $path = trim($path, '/');

        return $path === '' ? '%' : addcslashes($path, '%_\\') . '/%';
    }

    /** Binary data is bound as a stream (PDO::PARAM_LOB): PostgreSQL refuses raw binary as a plain string. */
    private function insertChunk(int $fileId, int $seq, string $data): void
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $data);
        rewind($stream);

        $statement = DB::connection()->getPdo()->prepare('INSERT INTO stored_file_chunks (stored_file_id, seq, data) VALUES (?, ?, ?)');
        $statement->bindValue(1, $fileId, PDO::PARAM_INT);
        $statement->bindValue(2, $seq, PDO::PARAM_INT);
        $statement->bindValue(3, $stream, PDO::PARAM_LOB);   // bindValue: bindParam would overwrite $stream with a string
        $statement->execute();

        fclose($stream);
    }
}
