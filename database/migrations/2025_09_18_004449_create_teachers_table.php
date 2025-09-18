<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            
            // User relationship
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Professional Information
            $table->string('employee_code')->unique();
            $table->string('identification_number')->unique();
            $table->enum('identification_type', ['CC', 'CE', 'PP'])->default('CC');
            $table->string('specialization')->nullable();
            $table->enum('education_level', [
                'Bachillerato',
                'Técnico',
                'Tecnológico', 
                'Licenciatura',
                'Especialización',
                'Maestría',
                'Doctorado'
            ])->default('Licenciatura');
            
            // Employment Information
            $table->date('hire_date');
            $table->enum('status', ['active', 'inactive', 'on_leave', 'terminated'])->default('active');
            $table->decimal('salary', 10, 2)->nullable();
            $table->enum('contract_type', ['indefinido', 'temporal', 'catedra', 'practicante'])->default('indefinido');
            
            // Contact Information
            $table->string('phone', 50)->nullable();
            $table->string('emergency_contact');
            $table->string('emergency_phone', 50);
            
            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['status']);
            $table->index(['specialization']);
            $table->index(['hire_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};