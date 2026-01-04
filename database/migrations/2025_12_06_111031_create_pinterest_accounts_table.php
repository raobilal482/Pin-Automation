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
    Schema::create('pinterest_accounts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Main login user

        $table->string('nickname'); // User apni marzi ka naam dega e.g. "My Food Blog"
        $table->string('pinterest_user_id')->unique(); // Pinterest ki taraf se ID
        $table->string('username')->nullable();
        $table->string('avatar_url')->nullable(); // Account ki DP

        // Tokens
        $table->text('access_token');
        $table->text('refresh_token')->nullable();
        $table->timestamp('token_expires_at')->nullable();

        $table->boolean('is_active')->default(true); // Account on/off switch
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinterest_accounts');
    }
};
