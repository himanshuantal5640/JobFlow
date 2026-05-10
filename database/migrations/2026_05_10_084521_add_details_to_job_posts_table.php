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
        Schema::table('job_posts', function (Blueprint $table) {
            $table->string('status')->default('active');
            $table->string('department')->nullable();
            $table->string('work_mode')->nullable(); // Remote, On-site, Hybrid
            $table->string('experience')->nullable(); // Entry, Mid, Senior, Lead
            $table->string('job_type')->nullable(); // Full-time, Part-time, Contract, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropColumn(['status', 'department', 'work_mode', 'experience', 'job_type']);
        });
    }
};
