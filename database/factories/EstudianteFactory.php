<?php

namespace Database\Factories;

use App\Models\Estudiante;
use App\Models\Grado;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstudianteFactory extends Factory
{
    protected $model = Estudiante::class;

    public function definition(): array
    {
        // Obtener datos geográficos para Colombia
        $country = Country::where('code', 'CO')->first();
        $state = $country ? State::where('country_id', $country->id)->inRandomOrder()->first() : null;
        $city = $state ? City::where('state_id', $state->id)->inRandomOrder()->first() : null;
        $grado = Grado::inRandomOrder()->first();

        return [
            // Información personal
            'nombres' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName() . ' ' . $this->faker->lastName(),
            'tipo_documento' => $this->faker->randomElement(['registro_civil', 'tarjeta_identidad', 'cedula']),
            'documento_identidad' => $this->faker->unique()->numerify('##########'),
            'genero' => $this->faker->randomElement(['masculino', 'femenino']),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('-18 years', '-5 years')->format('Y-m-d'),
            
            // Geografía
            'birth_country_id' => $country?->id ?? 1,
            'birth_state_id' => $state?->id ?? 1,
            'birth_city_id' => $city?->id ?? 1,
            
            // Información de contacto
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->phoneNumber(),
            'email' => $this->faker->optional(0.7)->email(),
            'contacto_emergencia_nombre' => $this->faker->name(),
            'contacto_emergencia_telefono' => $this->faker->phoneNumber(),
            'contacto_emergencia_relacion' => $this->faker->randomElement(['Madre', 'Padre', 'Abuelo', 'Abuela', 'Tío', 'Tía']),
            
            // Información del padre
            'padre_nombres' => $this->faker->optional(0.8)->firstName(),
            'padre_apellidos' => $this->faker->optional(0.8)->lastName(),
            'padre_tipo_documento' => $this->faker->optional(0.8)->randomElement(['cedula', 'pasaporte']),
            'padre_documento' => $this->faker->optional(0.8)->numerify('##########'),
            'padre_telefono' => $this->faker->optional(0.8)->phoneNumber(),
            'padre_email' => $this->faker->optional(0.5)->email(),
            'padre_ocupacion' => $this->faker->optional(0.6)->jobTitle(),
            'padre_lugar_trabajo' => $this->faker->optional(0.6)->company(),
            'padre_autorizado_recoger' => $this->faker->boolean(80),
            
            // Información de la madre
            'madre_nombres' => $this->faker->optional(0.9)->firstName(),
            'madre_apellidos' => $this->faker->optional(0.9)->lastName(),
            'madre_tipo_documento' => $this->faker->optional(0.9)->randomElement(['cedula', 'pasaporte']),
            'madre_documento' => $this->faker->optional(0.9)->numerify('##########'),
            'madre_telefono' => $this->faker->optional(0.9)->phoneNumber(),
            'madre_email' => $this->faker->optional(0.5)->email(),
            'madre_ocupacion' => $this->faker->optional(0.6)->jobTitle(),
            'madre_lugar_trabajo' => $this->faker->optional(0.6)->company(),
            'madre_autorizada_recoger' => $this->faker->boolean(85),
            
            // Acudiente adicional
            'tiene_acudiente_adicional' => $this->faker->boolean(20),
            'acudiente_nombres' => $this->faker->optional(0.2)->firstName(),
            'acudiente_apellidos' => $this->faker->optional(0.2)->lastName(),
            'acudiente_tipo_documento' => $this->faker->optional(0.2)->randomElement(['cedula', 'pasaporte']),
            'acudiente_documento' => $this->faker->optional(0.2)->numerify('##########'),
            'acudiente_parentesco' => $this->faker->optional(0.2)->randomElement(['Abuelo', 'Abuela', 'Tío', 'Tía', 'Hermano']),
            'acudiente_telefono' => $this->faker->optional(0.2)->phoneNumber(),
            'acudiente_email' => $this->faker->optional(0.1)->email(),
            
            // Información académica
            'grado_id' => $grado?->id ?? 1,
            'fecha_ingreso' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'codigo_estudiante' => null, // Se genera automáticamente
            'estado' => $this->faker->randomElement(['activo', 'activo', 'activo', 'activo', 'inactivo', 'retirado']), // 66% activo
            'es_estudiante_nuevo' => $this->faker->boolean(70),
            'colegio_procedencia' => $this->faker->optional(0.3)->company() . ' School',
            'ultimo_grado_cursado' => $this->faker->optional(0.3)->randomElement(['Transición', 'Primero', 'Segundo', 'Tercero']),
            'ano_finalizacion' => $this->faker->optional(0.3)->numberBetween(2020, 2024),
            'tiene_certificados_pendientes' => $this->faker->boolean(10),
            'observaciones_academicas' => $this->faker->optional(0.3)->sentence(),
            
            // Información médica
            'tipo_sangre' => $this->faker->randomElement(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']),
            'eps' => $this->faker->randomElement(['Sura EPS', 'Nueva EPS', 'Sanitas', 'Compensar', 'Famisanar', 'Cafesalud']),
            'numero_afiliacion_eps' => $this->faker->optional(0.8)->numerify('##########'),
            'alergias' => $this->faker->optional(0.2)->sentence(),
            'medicamentos' => $this->faker->optional(0.1)->sentence(),
            'condiciones_medicas' => $this->faker->optional(0.1)->sentence(),
            'restricciones_fisicas' => $this->faker->optional(0.05)->sentence(),
            
            // Archivos y observaciones
            'foto' => null,
            'observaciones' => $this->faker->optional(0.3)->paragraph(),
        ];
    }

    /**
     * Estado específico: activo
     */
    public function activo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'activo',
        ]);
    }

    /**
     * Estado específico: inactivo
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'inactivo',
        ]);
    }

    /**
     * Estado específico: retirado
     */
    public function retirado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'retirado',
        ]);
    }

    /**
     * Estudiante nuevo
     */
    public function nuevo(): static
    {
        return $this->state(fn (array $attributes) => [
            'es_estudiante_nuevo' => true,
            'colegio_procedencia' => null,
            'ultimo_grado_cursado' => null,
            'ano_finalizacion' => null,
        ]);
    }

    /**
     * Estudiante transferido
     */
    public function transferido(): static
    {
        return $this->state(fn (array $attributes) => [
            'es_estudiante_nuevo' => false,
            'colegio_procedencia' => $this->faker->company() . ' School',
            'ultimo_grado_cursado' => $this->faker->randomElement(['Transición', 'Primero', 'Segundo', 'Tercero']),
            'ano_finalizacion' => $this->faker->numberBetween(2020, 2024),
        ]);
    }

    /**
     * Con información completa de padres
     */
    public function conPadresCompletos(): static
    {
        return $this->state(fn (array $attributes) => [
            'padre_nombres' => $this->faker->firstName(),
            'padre_apellidos' => $this->faker->lastName(),
            'padre_tipo_documento' => 'cedula',
            'padre_documento' => $this->faker->numerify('##########'),
            'padre_telefono' => $this->faker->phoneNumber(),
            'madre_nombres' => $this->faker->firstName(),
            'madre_apellidos' => $this->faker->lastName(),
            'madre_tipo_documento' => 'cedula',
            'madre_documento' => $this->faker->numerify('##########'),
            'madre_telefono' => $this->faker->phoneNumber(),
        ]);
    }
}