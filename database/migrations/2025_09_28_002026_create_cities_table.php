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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
            $table->string('name');
            $table->string('code', 10)->nullable(); // Código DANE u oficial
            $table->boolean('is_capital')->default(false); // Si es capital del estado
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['state_id', 'active', 'name']);
            $table->index(['is_capital']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
