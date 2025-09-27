<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@gestionacademica.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Crear usuario docente
        User::create([
            'name' => 'María García',
            'email' => 'docente@gestionacademica.com',
            'password' => bcrypt('password'),
            'role' => 'docente',
        ]);

        // Crear usuario padre
        User::create([
            'name' => 'Juan Pérez',
            'email' => 'padre@gestionacademica.com',
            'password' => bcrypt('password'),
            'role' => 'padre',
        ]);
    }
}
