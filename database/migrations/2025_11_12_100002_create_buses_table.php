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
        Schema::create('buses', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name');
            $table->string('plate')->unique(); // 'plate' thaan logical key, namma unique-ah vechikalam
            $table->integer('capacity');
            $table->string('photo_path')->nullable();
            
            // Real-time location columns
            $table->decimal('current_lat', 10, 7)->nullable(); // Precision for latitude
            $table->decimal('current_lon', 10, 7)->nullable(); // Precision for longitude
            
            $table->timestamps(); // Ithu 'created_at' matrum 'updated_at' create pannum
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};