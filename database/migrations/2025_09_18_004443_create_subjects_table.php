<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->text('description')->nullable();
            
            // Subject Classification
            $table->enum('area', [
                'mathematics',
                'language', 
                'science',
                'social_studies',
                'arts',
                'physical_education',
                'english',
                'religion',
                'ethics',
                'technology'
            ]);
            $table->boolean('is_main_subject')->default(false);
            $table->integer('hours_per_week')->default(2);
            $table->enum('academic_level', ['preschool', 'elementary', 'middle', 'high']);
            
            // Status and Organization
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('color', 7)->default('#3B82F6'); // Hex color for UI
            
            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['area', 'academic_level']);
            $table->index(['status', 'academic_level']);
            $table->index('is_main_subject');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};