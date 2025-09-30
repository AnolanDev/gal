<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grados = [
            // Preescolar
            ['nombre' => 'Prejardín', 'nivel' => 'preescolar', 'orden' => 1],
            ['nombre' => 'Jardín', 'nivel' => 'preescolar', 'orden' => 2],
            ['nombre' => 'Transición', 'nivel' => 'preescolar', 'orden' => 3],
            
            // Primaria
            ['nombre' => 'Primero', 'nivel' => 'primaria', 'orden' => 4],
            ['nombre' => 'Segundo', 'nivel' => 'primaria', 'orden' => 5],
            ['nombre' => 'Tercero', 'nivel' => 'primaria', 'orden' => 6],
            ['nombre' => 'Cuarto', 'nivel' => 'primaria', 'orden' => 7],
            ['nombre' => 'Quinto', 'nivel' => 'primaria', 'orden' => 8],
            
            // Secundaria
            ['nombre' => 'Sexto', 'nivel' => 'secundaria', 'orden' => 9],
            ['nombre' => 'Séptimo', 'nivel' => 'secundaria', 'orden' => 10],
            ['nombre' => 'Octavo', 'nivel' => 'secundaria', 'orden' => 11],
            ['nombre' => 'Noveno', 'nivel' => 'secundaria', 'orden' => 12],
            ['nombre' => 'Décimo', 'nivel' => 'secundaria', 'orden' => 13],
            ['nombre' => 'Once', 'nivel' => 'secundaria', 'orden' => 14],
        ];

        foreach ($grados as $grado) {
            \App\Models\Grado::create([
                'nombre' => $grado['nombre'],
                'nivel' => $grado['nivel'],
                'orden' => $grado['orden'],
                'descripcion' => "Grado {$grado['nombre']} - Nivel {$grado['nivel']}",
                'activo' => true,
            ]);
        }
    }
}
