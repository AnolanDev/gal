<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario Administrador
        User::firstOrCreate(
            ['email' => 'admin@gestionacademica.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@gestionacademica.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now()
            ]
        );

        // Crear usuario Docente
        User::firstOrCreate(
            ['email' => 'docente@gestionacademica.com'],
            [
                'name' => 'Docente',
                'email' => 'docente@gestionacademica.com',
                'password' => Hash::make('password'),
                'role' => 'docente',
                'email_verified_at' => now()
            ]
        );

        // Crear usuario Padre
        User::firstOrCreate(
            ['email' => 'padre@gestionacademica.com'],
            [
                'name' => 'Padre de Familia',
                'email' => 'padre@gestionacademica.com',
                'password' => Hash::make('password'),
                'role' => 'padre',
                'email_verified_at' => now()
            ]
        );

        $this->command->info('✅ Usuarios de prueba creados exitosamente:');
        $this->command->line('- admin@gestionacademica.com / password (Administrador)');
        $this->command->line('- docente@gestionacademica.com / password (Docente)');
        $this->command->line('- padre@gestionacademica.com / password (Padre de Familia)');
    }
}