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
        Schema::create('routes', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name')->unique(); // 'name' thaan logical key, namma unique-ah vechikalam
            $table->string('start');
            $table->string('end');
            
            // Foreign key (links to buses.plate)
            $table->string('bus_plate')->nullable()->index(); // Add index for faster search
            
            $table->timestamps(); // Ithu 'created_at' matrum 'updated_at' create pannum
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};