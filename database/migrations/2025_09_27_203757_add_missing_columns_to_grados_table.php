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
        Schema::table('grados', function (Blueprint $table) {
            $table->enum('nivel', ['preescolar', 'primaria', 'secundaria'])->change();
            $table->text('descripcion')->nullable()->after('seccion');
            $table->integer('orden')->default(0)->after('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grados', function (Blueprint $table) {
            $table->dropColumn(['descripcion', 'orden']);
            $table->enum('nivel', ['preescolar', 'primaria'])->change();
        });
    }
};
