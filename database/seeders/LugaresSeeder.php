<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LugaresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Países principales de la región
        $colombia = \App\Models\Pais::create([
            'nombre' => 'Colombia',
            'codigo_iso' => 'CO',
            'activo' => true,
        ]);

        $venezuela = \App\Models\Pais::create([
            'nombre' => 'Venezuela',
            'codigo_iso' => 'VE',
            'activo' => true,
        ]);

        $panama = \App\Models\Pais::create([
            'nombre' => 'Panamá',
            'codigo_iso' => 'PA',
            'activo' => true,
        ]);

        // Departamentos de Colombia (principales)
        $atlantico = \App\Models\Departamento::create([
            'nombre' => 'Atlántico',
            'codigo' => '08',
            'pais_id' => $colombia->id,
            'activo' => true,
        ]);

        $bolivar = \App\Models\Departamento::create([
            'nombre' => 'Bolívar',
            'codigo' => '13',
            'pais_id' => $colombia->id,
            'activo' => true,
        ]);

        $magdalena = \App\Models\Departamento::create([
            'nombre' => 'Magdalena',
            'codigo' => '47',
            'pais_id' => $colombia->id,
            'activo' => true,
        ]);

        $laguajira = \App\Models\Departamento::create([
            'nombre' => 'La Guajira',
            'codigo' => '44',
            'pais_id' => $colombia->id,
            'activo' => true,
        ]);

        // Estados de Venezuela (principales)
        $zulia = \App\Models\Departamento::create([
            'nombre' => 'Zulia',
            'codigo' => 'ZU',
            'pais_id' => $venezuela->id,
            'activo' => true,
        ]);

        $miranda = \App\Models\Departamento::create([
            'nombre' => 'Miranda',
            'codigo' => 'MI',
            'pais_id' => $venezuela->id,
            'activo' => true,
        ]);

        // Provincias de Panamá
        $panama_prov = \App\Models\Departamento::create([
            'nombre' => 'Panamá',
            'codigo' => 'PA',
            'pais_id' => $panama->id,
            'activo' => true,
        ]);

        // Ciudades de Colombia
        \App\Models\Ciudad::create(['nombre' => 'Barranquilla', 'codigo' => '08001', 'departamento_id' => $atlantico->id, 'activo' => true]);
        \App\Models\Ciudad::create(['nombre' => 'Soledad', 'codigo' => '08758', 'departamento_id' => $atlantico->id, 'activo' => true]);
        \App\Models\Ciudad::create(['nombre' => 'Malambo', 'codigo' => '08433', 'departamento_id' => $atlantico->id, 'activo' => true]);

        \App\Models\Ciudad::create(['nombre' => 'Cartagena', 'codigo' => '13001', 'departamento_id' => $bolivar->id, 'activo' => true]);
        \App\Models\Ciudad::create(['nombre' => 'Magangué', 'codigo' => '13430', 'departamento_id' => $bolivar->id, 'activo' => true]);
        \App\Models\Ciudad::create(['nombre' => 'Turbaco', 'codigo' => '13836', 'departamento_id' => $bolivar->id, 'activo' => true]);

        \App\Models\Ciudad::create(['nombre' => 'Santa Marta', 'codigo' => '47001', 'departamento_id' => $magdalena->id, 'activo' => true]);
        \App\Models\Ciudad::create(['nombre' => 'Ciénaga', 'codigo' => '47189', 'departamento_id' => $magdalena->id, 'activo' => true]);

        \App\Models\Ciudad::create(['nombre' => 'Riohacha', 'codigo' => '44001', 'departamento_id' => $laguajira->id, 'activo' => true]);
        \App\Models\Ciudad::create(['nombre' => 'Maicao', 'codigo' => '44430', 'departamento_id' => $laguajira->id, 'activo' => true]);

        // Ciudades de Venezuela
        \App\Models\Ciudad::create(['nombre' => 'Maracaibo', 'departamento_id' => $zulia->id, 'activo' => true]);
        \App\Models\Ciudad::create(['nombre' => 'Cabimas', 'departamento_id' => $zulia->id, 'activo' => true]);

        \App\Models\Ciudad::create(['nombre' => 'Caracas', 'departamento_id' => $miranda->id, 'activo' => true]);
        \App\Models\Ciudad::create(['nombre' => 'Los Teques', 'departamento_id' => $miranda->id, 'activo' => true]);

        // Ciudades de Panamá
        \App\Models\Ciudad::create(['nombre' => 'Ciudad de Panamá', 'departamento_id' => $panama_prov->id, 'activo' => true]);
        \App\Models\Ciudad::create(['nombre' => 'San Miguelito', 'departamento_id' => $panama_prov->id, 'activo' => true]);
    }
}
