<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Tambah kolom status jika belum ada
            if (!Schema::hasColumn('reviews', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('video');
            }
        });

        // Rename kolom komentar ke comment
        if (Schema::hasColumn('reviews', 'komentar') && !Schema::hasColumn('reviews', 'comment')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->renameColumn('komentar', 'comment');
            });
        }

        // Rename kolom foto ke photos dan ubah tipe ke JSON
        if (Schema::hasColumn('reviews', 'foto') && !Schema::hasColumn('reviews', 'photos')) {
            // Ubah data lama ke format JSON
            DB::statement('ALTER TABLE reviews CHANGE foto photos JSON NULL');
        }
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'status')) {
                $table->dropColumn('status');
            }
        });

        if (Schema::hasColumn('reviews', 'comment')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->renameColumn('comment', 'komentar');
            });
        }

        if (Schema::hasColumn('reviews', 'photos')) {
            DB::statement('ALTER TABLE reviews CHANGE photos foto VARCHAR(255) NULL');
        }
    }
};

