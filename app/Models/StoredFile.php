<?php

namespace App\Models;

use App\Filesystem\DatabaseAdapter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/** One uploaded file kept in the database; its bytes are in stored_file_chunks (see the migration). */
class StoredFile extends Model
{
    protected $fillable = ['path', 'mime_type', 'size', 'sha1'];

    /** Chunks fetched per query: 4 x 256 KB is about 1 MB in memory at a time. A photo is one query; a 35 MB PDF is 35. */
    private const CHUNKS_PER_QUERY = 4;

    /** The file's bytes, one chunk at a time, so a large PDF never has to sit in memory. */
    public function chunks(): \Generator
    {
        $total = (int) ceil($this->size / DatabaseAdapter::CHUNK_SIZE);

        for ($from = 0; $from < $total; $from += self::CHUNKS_PER_QUERY) {
            $rows = DB::table('stored_file_chunks')
                ->where('stored_file_id', $this->id)
                ->where('seq', '>=', $from)
                ->where('seq', '<', $from + self::CHUNKS_PER_QUERY)
                ->orderBy('seq')
                ->pluck('data');

            foreach ($rows as $data) {
                // PostgreSQL hands binary columns back as a stream, MySQL as a string
                yield is_resource($data) ? stream_get_contents($data) : $data;
            }
        }
    }
}
