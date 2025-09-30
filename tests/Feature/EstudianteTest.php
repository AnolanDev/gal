<?php

namespace Tests\Feature;

use App\Models\Estudiante;
use App\Models\Grado;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstudianteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear datos básicos para las pruebas
        $this->seed([
            \Database\Seeders\CountriesSeeder::class,
            \Database\Seeders\ColombianStatesSeeder::class,
            \Database\Seeders\CompleteColombiaCitiesSeeder::class,
            \Database\Seeders\GradoSeeder::class,
        ]);
    }

    /** @test */
    public function it_can_create_a_student_with_complete_data()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $grado = Grado::first();
        $country = Country::where('code', 'CO')->first();
        
        if (!$country) {
            $this->markTestSkipped('No countries found in database');
        }
        
        $state = State::where('country_id', $country->id)->first();
        if (!$state) {
            $this->markTestSkipped('No states found for Colombia');
        }
        
        $city = City::where('state_id', $state->id)->first();
        if (!$city) {
            $this->markTestSkipped('No cities found for the state');
        }

        $studentData = [
            // Información personal
            'nombres' => 'Juan Carlos',
            'apellidos' => 'Pérez García',
            'tipo_documento' => 'cedula',
            'documento_identidad' => '1234567890',
            'genero' => 'masculino',
            'fecha_nacimiento' => '2010-05-15',
            'birth_country_id' => $country->id,
            'birth_state_id' => $state->id,
            'birth_city_id' => $city->id,
            
            // Información de contacto
            'direccion' => 'Calle 123 #45-67',
            'telefono' => '3001234567',
            'email' => 'juan.perez@example.com',
            'contacto_emergencia_nombre' => 'María García',
            'contacto_emergencia_telefono' => '3009876543',
            'contacto_emergencia_relacion' => 'Madre',
            
            // Información de padres
            'padre_nombres' => 'Carlos Alberto',
            'padre_apellidos' => 'Pérez López',
            'padre_tipo_documento' => 'cedula',
            'padre_documento' => '12345678',
            'padre_telefono' => '3001111111',
            'padre_email' => 'carlos.perez@example.com',
            
            'madre_nombres' => 'María Elena',
            'madre_apellidos' => 'García Rodríguez',
            'madre_tipo_documento' => 'cedula',
            'madre_documento' => '87654321',
            'madre_telefono' => '3002222222',
            'madre_email' => 'maria.garcia@example.com',
            
            // Información académica
            'grado_id' => $grado->id,
            'fecha_ingreso' => '2025-01-15',
            'es_estudiante_nuevo' => true,
            
            // Información médica
            'tipo_sangre' => 'O+',
            'eps' => 'Sura EPS',
        ];

        $response = $this->actingAs($user)->post(route('estudiantes.store'), $studentData);

        $response->assertRedirect();
        $this->assertDatabaseHas('estudiantes', [
            'nombres' => 'Juan Carlos',
            'apellidos' => 'Pérez García',
            'documento_identidad' => '1234567890',
        ]);

        $estudiante = Estudiante::where('documento_identidad', '1234567890')->first();
        $this->assertNotNull($estudiante);
        $this->assertEquals('Juan Carlos Pérez García', $estudiante->nombre_completo);
        $this->assertTrue($estudiante->tienePadreCompleto());
        $this->assertTrue($estudiante->tieneMadreCompleta());
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('estudiantes.store'), []);

        $response->assertSessionHasErrors([
            'nombres',
            'apellidos',
            'documento_identidad',
            'tipo_documento',
            'genero',
            'fecha_nacimiento',
            'birth_country_id',
            'birth_state_id',
            'birth_city_id',
            'direccion',
            'contacto_emergencia_nombre',
            'contacto_emergencia_telefono',
            'contacto_emergencia_relacion',
            'grado_id',
            'fecha_ingreso',
            'tipo_sangre',
            'eps',
        ]);
    }

    /** @test */
    public function it_requires_at_least_one_parent()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $grado = Grado::first();
        $country = Country::where('code', 'CO')->first();
        
        if (!$country) {
            $this->markTestSkipped('No countries found in database');
        }
        
        $state = State::where('country_id', $country->id)->first();
        if (!$state) {
            $this->markTestSkipped('No states found for Colombia');
        }
        
        $city = City::where('state_id', $state->id)->first();
        if (!$city) {
            $this->markTestSkipped('No cities found for the state');
        }

        $studentData = [
            'nombres' => 'Juan Carlos',
            'apellidos' => 'Pérez García',
            'tipo_documento' => 'cedula',
            'documento_identidad' => '1234567890',
            'genero' => 'masculino',
            'fecha_nacimiento' => '2010-05-15',
            'birth_country_id' => $country->id,
            'birth_state_id' => $state->id,
            'birth_city_id' => $city->id,
            'direccion' => 'Calle 123 #45-67',
            'contacto_emergencia_nombre' => 'María García',
            'contacto_emergencia_telefono' => '3009876543',
            'contacto_emergencia_relacion' => 'Madre',
            'grado_id' => $grado->id,
            'fecha_ingreso' => '2025-01-15',
            'tipo_sangre' => 'O+',
            'eps' => 'Sura EPS',
            // Sin información de padres
        ];

        $response = $this->actingAs($user)->post(route('estudiantes.store'), $studentData);

        $response->assertSessionHasErrors(['padre_nombres']);
    }

    /** @test */
    public function it_generates_student_code_automatically()
    {
        $estudiante = Estudiante::factory()->create();
        
        $this->assertNotEmpty($estudiante->codigo_estudiante);
        $this->assertStringStartsWith(date('Y'), $estudiante->codigo_estudiante);
    }

    /** @test */
    public function it_can_search_students()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $estudiante = Estudiante::factory()->create([
            'nombres' => 'Juan Carlos',
            'apellidos' => 'Pérez García',
            'documento_identidad' => '1234567890'
        ]);

        $response = $this->actingAs($user)->get(route('estudiantes.index', ['search' => 'Juan']));
        $response->assertOk();

        $response = $this->actingAs($user)->get(route('estudiantes.index', ['search' => 'Pérez']));
        $response->assertOk();

        $response = $this->actingAs($user)->get(route('estudiantes.index', ['search' => '1234567890']));
        $response->assertOk();
    }

    /** @test */
    public function it_can_update_student_information()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $estudiante = Estudiante::factory()->create([
            'nombres' => 'Juan Carlos',
            'apellidos' => 'Pérez García'
        ]);

        $updateData = [
            'nombres' => 'Juan Carlos Actualizado',
            'apellidos' => 'Pérez García',
            'tipo_documento' => $estudiante->tipo_documento,
            'documento_identidad' => $estudiante->documento_identidad,
            'genero' => $estudiante->genero,
            'fecha_nacimiento' => $estudiante->fecha_nacimiento,
            'birth_country_id' => $estudiante->birth_country_id,
            'birth_state_id' => $estudiante->birth_state_id,
            'birth_city_id' => $estudiante->birth_city_id,
            'direccion' => $estudiante->direccion,
            'contacto_emergencia_nombre' => $estudiante->contacto_emergencia_nombre,
            'contacto_emergencia_telefono' => $estudiante->contacto_emergencia_telefono,
            'contacto_emergencia_relacion' => $estudiante->contacto_emergencia_relacion,
            'grado_id' => $estudiante->grado_id,
            'fecha_ingreso' => $estudiante->fecha_ingreso,
            'tipo_sangre' => $estudiante->tipo_sangre,
            'eps' => $estudiante->eps,
        ];

        $response = $this->actingAs($user)->put(route('estudiantes.update', $estudiante), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('estudiantes', [
            'id' => $estudiante->id,
            'nombres' => 'Juan Carlos Actualizado'
        ]);
    }

    /** @test */
    public function it_can_filter_students_by_status()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        Estudiante::factory()->create(['estado' => 'activo']);
        Estudiante::factory()->create(['estado' => 'inactivo']);
        Estudiante::factory()->create(['estado' => 'retirado']);

        $response = $this->actingAs($user)->get(route('estudiantes.index', ['estado' => 'activo']));
        $response->assertOk();

        $response = $this->actingAs($user)->get(route('estudiantes.index', ['estado' => 'inactivo']));
        $response->assertOk();

        $response = $this->actingAs($user)->get(route('estudiantes.index', ['estado' => 'retirado']));
        $response->assertOk();
    }
}