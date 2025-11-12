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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('photo_path')->nullable();
            
            // App login columns
            $table->string('app_username')->unique();
            $table->string('app_password');
            
            // Foreign key (aana namma constraint add pannala, just column)
            $table->string('bus_plate')->nullable()->index(); // Add index for faster search
            
            // OTP and verification columns
            $table->boolean('is_verified')->default(false);
            $table->string('otp_code', 10)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->integer('otp_attempt_count')->default(0);
            $table->timestamp('otp_locked_until')->nullable();
            
            $table->timestamps(); // Ithu 'created_at' matrum 'updated_at' create pannum
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};