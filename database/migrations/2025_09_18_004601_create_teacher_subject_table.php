<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('grade_id')->constrained()->onDelete('cascade');
            $table->integer('academic_period_id')->default(1);
            $table->timestamps();

            $table->unique(['teacher_id', 'subject_id', 'grade_id', 'academic_period_id'], 'teacher_subject_grade_period_unique');
            $table->index(['academic_period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_subject');
    }
};