<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador
        \App\Models\User::create([
            'name' => 'Administrador',
            'email' => 'admin@gestionacademica.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Usuario Docente
        \App\Models\User::create([
            'name' => 'Docente',
            'email' => 'docente@gestionacademica.com',
            'password' => bcrypt('password'),
            'role' => 'docente',
            'email_verified_at' => now(),
        ]);

        // Usuario Padre
        \App\Models\User::create([
            'name' => 'Padre',
            'email' => 'padre@gestionacademica.com',
            'password' => bcrypt('password'),
            'role' => 'padre',
            'email_verified_at' => now(),
        ]);

        // Crear algunos padres de familia adicionales de ejemplo
        \App\Models\User::create([
            'name' => 'María García',
            'email' => 'maria.garcia@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'padre',
            'email_verified_at' => now(),
        ]);

        \App\Models\User::create([
            'name' => 'Carlos Rodríguez',
            'email' => 'carlos.rodriguez@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'padre',
            'email_verified_at' => now(),
        ]);
    }
}
