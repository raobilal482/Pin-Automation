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
    Schema::table('posts', function (Blueprint $table) {
        // Agar pehle se nahi hain, to add kardo
        if (!Schema::hasColumn('posts', 'published_at')) {
            $table->timestamp('published_at')->nullable();
        }
        if (!Schema::hasColumn('posts', 'pinterest_pin_id')) {
            $table->string('pinterest_pin_id')->nullable();
        }
    });
}

public function down(): void
{
    Schema::table('posts', function (Blueprint $table) {
        $table->dropColumn(['published_at', 'pinterest_pin_id']);
    });
}
};
