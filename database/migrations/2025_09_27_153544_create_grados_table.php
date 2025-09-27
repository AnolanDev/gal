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
        Schema::create('grados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: "Preescolar", "1°", "2°", etc.
            $table->enum('nivel', ['preescolar', 'primaria']);
            $table->string('seccion')->nullable(); // Ej: "A", "B", "C"
            $table->foreignId('docente_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('capacidad_maxima')->default(30);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grados');
    }
};
