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
        Schema::create('employee_assessed_response_texts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_assessed_id')->constrained()->onDelete('cascade');
            $table->string('aspect');
            $table->string('question');
            $table->string('option');
            $table->float('score'); //Nilai Aktual x Beban (weight)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_assessed_response_texts');
    }
};