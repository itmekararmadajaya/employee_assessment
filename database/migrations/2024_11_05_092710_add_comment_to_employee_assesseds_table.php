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
        Schema::table('employee_assesseds', function (Blueprint $table) {
            $table->text('job_description')->nullable();
            $table->text('assessor_comments')->nullable();
            $table->text('approver_comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_assesseds', function (Blueprint $table) {
            $table->dropColumn(['job_description','assessor_comments','approver_comments']);
        });
    }
};
