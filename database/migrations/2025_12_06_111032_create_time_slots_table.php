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
    Schema::create('time_slots', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pinterest_account_id')->constrained()->onDelete('cascade');

        $table->time('start_time'); // 13:00:00
        $table->time('end_time');   // 15:00:00
        $table->integer('posts_count')->default(1); // 5 posts

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
