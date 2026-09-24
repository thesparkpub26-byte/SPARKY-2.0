<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Videos submitted by the broadcasting team ride the same review pipeline as
// articles (draft → submitted → endorsed → published), so they are stored as
// articles of type "video" carrying a YouTube link and a video category
// (Documentary / Reel / Telesiklab). Their crew (videographer, video editor)
// get their own task types.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'video_url')) {
                $table->string('video_url', 500)->nullable()->after('cover_image');
            }
            if (!Schema::hasColumn('articles', 'video_category')) {
                $table->string('video_category', 50)->nullable()->after('video_url');
            }
        });

        DB::statement("ALTER TABLE articles MODIFY type ENUM('article','feature','opinion','photo_essay','illustration','video') NOT NULL DEFAULT 'article'");
        DB::statement("ALTER TABLE tasks MODIFY type ENUM('writing','illustration','photography','layout','editing','videography','video_editing') NOT NULL DEFAULT 'writing'");
    }

    public function down(): void
    {
        DB::table('tasks')->whereIn('type', ['videography', 'video_editing'])->update(['type' => 'layout']);
        DB::table('articles')->where('type', 'video')->update(['type' => 'article']);

        DB::statement("ALTER TABLE tasks MODIFY type ENUM('writing','illustration','photography','layout','editing') NOT NULL DEFAULT 'writing'");
        DB::statement("ALTER TABLE articles MODIFY type ENUM('article','feature','opinion','photo_essay','illustration') NOT NULL DEFAULT 'article'");

        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'video_category')) {
                $table->dropColumn('video_category');
            }
            if (Schema::hasColumn('articles', 'video_url')) {
                $table->dropColumn('video_url');
            }
        });
    }
};
