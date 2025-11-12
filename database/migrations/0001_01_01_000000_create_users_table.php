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
        Schema::create('users', function (Blueprint $table) {
            // Ithu default Laravel columns
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // --- Namma Custom Columns (Ippo Ithu Kandippa Work Aaganum) ---
            $table->string('phone', 20)->nullable(); 
            $table->string('institution_name')->nullable();
            $table->string('subscription_plan', 50)->nullable();
            $table->string('subscription_type', 20)->default('monthly');
            $table->decimal('payment_amount', 10, 2)->default(0.00);
            $table->string('payment_status', 20)->default('pending');
            $table->string('status', 20)->default('pending');
            // --- End ---
            
            $table->rememberToken();
            $table->timestamps(); // Ithu 'created_at' matrum 'updated_at' create pannum
        });

        // PUTHU MAATRAM: Intha lines-ah thooki matha files-kku anuppittom (athu already create aagiduchu)
        // Schema::create('password_reset_tokens', function (Blueprint $table) { ... });
        // Schema::create('sessions', function (Blueprint $table) { ... });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        // PUTHU MAATRAM: Matha drops-ah thookittom
    }
};