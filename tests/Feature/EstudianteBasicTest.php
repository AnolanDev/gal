<?php

namespace Tests\Feature;

use App\Models\Estudiante;
use App\Models\Grado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstudianteBasicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\GradoSeeder::class);
    }

    public function test_it_generates_student_code_automatically()
    {
        $grado = Grado::first();
        
        $estudiante = Estudiante::create([
            'nombres' => 'Juan Carlos',
            'apellidos' => 'Pérez García',
            'tipo_documento' => 'cedula',
            'documento_identidad' => '1234567890',
            'genero' => 'masculino',
            'fecha_nacimiento' => '2010-05-15',
            'birth_country_id' => 1,
            'birth_state_id' => 1,
            'birth_city_id' => 1,
            'direccion' => 'Calle 123 #45-67',
            'contacto_emergencia_nombre' => 'María García',
            'contacto_emergencia_telefono' => '3009876543',
            'contacto_emergencia_relacion' => 'Madre',
            'padre_nombres' => 'Carlos Alberto',
            'padre_apellidos' => 'Pérez López',
            'padre_tipo_documento' => 'cedula',
            'padre_documento' => '12345678',
            'padre_telefono' => '3001111111',
            'grado_id' => $grado->id,
            'fecha_ingreso' => '2025-01-15',
            'tipo_sangre' => 'O+',
            'eps' => 'Sura EPS',
        ]);
        
        $this->assertNotEmpty($estudiante->codigo_estudiante);
        $this->assertStringStartsWith(date('Y'), $estudiante->codigo_estudiante);
        $this->assertEquals('Juan Carlos Pérez García', $estudiante->nombre_completo);
    }

    public function test_it_validates_unique_documento()
    {
        $grado = Grado::first();
        
        // Crear primer estudiante
        Estudiante::create([
            'nombres' => 'Juan Carlos',
            'apellidos' => 'Pérez García',
            'tipo_documento' => 'cedula',
            'documento_identidad' => '1234567890',
            'genero' => 'masculino',
            'fecha_nacimiento' => '2010-05-15',
            'birth_country_id' => 1,
            'birth_state_id' => 1,
            'birth_city_id' => 1,
            'direccion' => 'Calle 123 #45-67',
            'contacto_emergencia_nombre' => 'María García',
            'contacto_emergencia_telefono' => '3009876543',
            'contacto_emergencia_relacion' => 'Madre',
            'padre_nombres' => 'Carlos Alberto',
            'padre_apellidos' => 'Pérez López',
            'padre_tipo_documento' => 'cedula',
            'padre_documento' => '12345678',
            'padre_telefono' => '3001111111',
            'grado_id' => $grado->id,
            'fecha_ingreso' => '2025-01-15',
            'tipo_sangre' => 'O+',
            'eps' => 'Sura EPS',
        ]);

        // Intentar crear segundo estudiante con mismo documento
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Estudiante::create([
            'nombres' => 'María Elena',
            'apellidos' => 'García López',
            'tipo_documento' => 'cedula',
            'documento_identidad' => '1234567890', // Mismo documento
            'genero' => 'femenino',
            'fecha_nacimiento' => '2010-05-15',
            'birth_country_id' => 1,
            'birth_state_id' => 1,
            'birth_city_id' => 1,
            'direccion' => 'Calle 456 #78-90',
            'contacto_emergencia_nombre' => 'Ana López',
            'contacto_emergencia_telefono' => '3001234567',
            'contacto_emergencia_relacion' => 'Madre',
            'madre_nombres' => 'Ana Elena',
            'madre_apellidos' => 'López García',
            'madre_tipo_documento' => 'cedula',
            'madre_documento' => '87654321',
            'madre_telefono' => '3002222222',
            'grado_id' => $grado->id,
            'fecha_ingreso' => '2025-01-15',
            'tipo_sangre' => 'A+',
            'eps' => 'Nueva EPS',
        ]);
    }

    public function test_it_has_proper_scopes()
    {
        $grado = Grado::first();
        
        // Crear estudiantes con diferentes estados
        Estudiante::factory()->count(3)->create(['estado' => 'activo', 'grado_id' => $grado->id]);
        Estudiante::factory()->count(2)->create(['estado' => 'inactivo', 'grado_id' => $grado->id]);
        Estudiante::factory()->count(1)->create(['estado' => 'retirado', 'grado_id' => $grado->id]);

        $this->assertEquals(3, Estudiante::activos()->count());
        $this->assertEquals(2, Estudiante::inactivos()->count());
        $this->assertEquals(1, Estudiante::retirados()->count());
        $this->assertEquals(6, Estudiante::count());
    }

    public function test_it_can_search_students()
    {
        $grado = Grado::first();
        
        Estudiante::factory()->create([
            'nombres' => 'Juan Carlos',
            'apellidos' => 'Pérez García',
            'documento_identidad' => '1234567890',
            'grado_id' => $grado->id
        ]);

        Estudiante::factory()->create([
            'nombres' => 'María Elena',
            'apellidos' => 'López Rodríguez',
            'documento_identidad' => '0987654321',
            'grado_id' => $grado->id
        ]);

        // Buscar por nombre
        $this->assertEquals(1, Estudiante::buscar('Juan')->count());
        $this->assertEquals(1, Estudiante::buscar('María')->count());
        
        // Buscar por apellido
        $this->assertEquals(1, Estudiante::buscar('Pérez')->count());
        $this->assertEquals(1, Estudiante::buscar('López')->count());
        
        // Buscar por documento
        $this->assertEquals(1, Estudiante::buscar('1234567890')->count());
        $this->assertEquals(1, Estudiante::buscar('0987654321')->count());
        
        // Buscar que no existe
        $this->assertEquals(0, Estudiante::buscar('NoExiste')->count());
    }

    public function test_parent_validation_methods()
    {
        $grado = Grado::first();
        
        // Estudiante solo con padre
        $estudianteSoloPadre = Estudiante::factory()->create([
            'padre_nombres' => 'Carlos',
            'padre_apellidos' => 'Pérez',
            'padre_documento' => '12345678',
            'padre_telefono' => '3001111111',
            'madre_nombres' => null,
            'madre_apellidos' => null,
            'madre_documento' => null,
            'madre_telefono' => null,
            'grado_id' => $grado->id
        ]);

        $this->assertTrue($estudianteSoloPadre->tienePadreCompleto());
        $this->assertFalse($estudianteSoloPadre->tieneMadreCompleta());
        $this->assertTrue($estudianteSoloPadre->tieneAlMenosUnPadre());

        // Estudiante solo con madre
        $estudianteSoloMadre = Estudiante::factory()->create([
            'padre_nombres' => null,
            'padre_apellidos' => null,
            'padre_documento' => null,
            'padre_telefono' => null,
            'madre_nombres' => 'María',
            'madre_apellidos' => 'García',
            'madre_documento' => '87654321',
            'madre_telefono' => '3002222222',
            'grado_id' => $grado->id
        ]);

        $this->assertFalse($estudianteSoloMadre->tienePadreCompleto());
        $this->assertTrue($estudianteSoloMadre->tieneMadreCompleta());
        $this->assertTrue($estudianteSoloMadre->tieneAlMenosUnPadre());
    }
}