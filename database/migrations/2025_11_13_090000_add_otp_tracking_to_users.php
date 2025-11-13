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
        Schema::table('drivers', function (Blueprint $table) {
            $table->unsignedSmallInteger('otp_sent_count')->default(0)->after('otp_attempt_count');
            $table->timestamp('otp_last_sent_at')->nullable()->after('otp_sent_count');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->unsignedSmallInteger('otp_sent_count')->default(0)->after('otp_attempt_count');
            $table->timestamp('otp_last_sent_at')->nullable()->after('otp_sent_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['otp_sent_count', 'otp_last_sent_at']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['otp_sent_count', 'otp_last_sent_at']);
        });
    }
};

