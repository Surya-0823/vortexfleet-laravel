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
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('token', 64)->unique(); // The token itself
            
            // Ithu thaan antha polymorphic relationship-oda columns
            // 'tokenable_id' thaan Driver (e.g., 5) allathu Student (e.g., 12) ID-ah save pannum
            $table->unsignedBigInteger('tokenable_id');
            // 'tokenable_type' thaan antha Model path-ah save pannum (e.g., 'App\Models\Driver')
            $table->string('tokenable_type');
            
            $table->timestamp('expires_at')->nullable();
            $table->timestamps(); // Ithu 'created_at' matrum 'updated_at' create pannum
            
            // Rendu polymorphic columns-kum serthu oru index create panrom
            $table->index(['tokenable_id', 'tokenable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_tokens');
    }
};