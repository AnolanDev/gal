<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name', 50); // Pre-Jardín, Jardín, Transición, 1°, 2°, etc.
            $table->enum('level', ['preschool', 'elementary', 'middle', 'high'])->default('elementary');
            $table->string('section', 10)->nullable(); // A, B, C, etc.
            $table->year('academic_year')->default(date('Y'));
            
            // Capacity and Organization
            $table->integer('max_students')->default(25);
            $table->string('classroom', 20)->nullable(); // Room number/name
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->text('description')->nullable();
            
            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['status', 'academic_year']);
            $table->index(['level', 'academic_year']);
            $table->unique(['name', 'section', 'academic_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};