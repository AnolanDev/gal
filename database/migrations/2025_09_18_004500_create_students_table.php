<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('code')->unique();
            $table->string('identification_number')->unique();
            $table->enum('identification_type', ['TI', 'RC', 'CC', 'CE', 'PP'])->default('TI');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->enum('gender', ['M', 'F', 'Other']);
            
            // Contact Information
            $table->text('address');
            $table->string('phone', 50)->nullable();
            $table->string('emergency_contact');
            $table->string('emergency_phone', 50);
            
            // Medical Information
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->json('medical_conditions')->nullable();
            
            // Academic Information
            $table->foreignId('grade_id')->constrained('grades')->onDelete('restrict');
            $table->foreignId('parent_user_id')->constrained('users')->onDelete('restrict');
            $table->date('enrollment_date');
            $table->enum('status', ['active', 'inactive', 'graduated', 'transferred'])->default('active');
            
            // Media
            $table->string('photo_path')->nullable();
            
            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['status', 'grade_id']);
            $table->index(['parent_user_id']);
            $table->index(['enrollment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};