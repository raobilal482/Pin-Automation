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
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pinterest_account_id')->constrained()->onDelete('cascade');

        // Image Info
        $table->string('image_path');
        $table->string('original_filename'); // Keyword nikalne ke liye

        // AI Content (Nullable taake bad mein fill ho)
        $table->string('keyword')->nullable();
        $table->string('title')->nullable();
        $table->text('description')->nullable();
        $table->string('destination_link')->nullable();

        // Scheduling & Status
        $table->string('status')->default('pending');
        $table->timestamp('scheduled_at')->nullable();

        // Pinterest Response
        $table->string('pinterest_pin_id')->nullable();
        $table->string('pin_url')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
