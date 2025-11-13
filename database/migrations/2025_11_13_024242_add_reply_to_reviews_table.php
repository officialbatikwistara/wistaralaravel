<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('reply')->nullable()->after('comment');
            $table->timestamp('replied_at')->nullable()->after('reply');
            $table->boolean('is_verified_purchase')->default(false)->after('status');
            $table->integer('helpful_count')->default(0)->after('is_verified_purchase');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['reply', 'replied_at', 'is_verified_purchase', 'helpful_count']);
        });
    }
};
