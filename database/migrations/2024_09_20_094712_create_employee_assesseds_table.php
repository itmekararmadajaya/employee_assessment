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
        Schema::create('employee_assesseds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_assessment_id')->constrained()->onDelete('cascade');
            $table->timestamp('assessment_date')->nullable();

            $table->unsignedBigInteger('employee_id')->nullable();;
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('employee_nik')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('employee_position')->nullable();
            $table->string('employee_section')->nullable();
            $table->string('employee_departement')->nullable();

            $table->unsignedBigInteger('assessor_id')->nullable();
            $table->foreign('assessor_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('assessor_nik')->nullable();
            $table->string('assessor_name')->nullable();
            $table->string('assessor_position')->nullable();
            $table->string('assessor_section')->nullable();
            $table->string('assessor_departement')->nullable();
            $table->enum('status', ['done', 'on_progress'])->default('on_progress');
            
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('employees')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->string('approver_nik')->nullable();
            $table->string('approver_name')->nullable();
            $table->string('approver_position')->nullable();
            $table->string('approver_section')->nullable();
            $table->string('approver_departement')->nullable();
            
            $table->integer('score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_assesseds');
    }
};
