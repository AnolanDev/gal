<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class ColombianCitiesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $colombia = Country::where('code', 'COL')->first();
        
        if (!$colombia) {
            $this->command->error('No se encontró Colombia en la base de datos.');
            return;
        }

        // Principales ciudades por departamento
        $citiesData = [
            // Antioquia
            'Antioquia' => [
                ['name' => 'Medellín', 'code' => '05001', 'is_capital' => true],
                ['name' => 'Bello', 'code' => '05088'],
                ['name' => 'Itagüí', 'code' => '05360'],
                ['name' => 'Envigado', 'code' => '05266'],
                ['name' => 'Sabaneta', 'code' => '05631'],
            ],
            
            // Bogotá D.C.
            'Bogotá D.C.' => [
                ['name' => 'Bogotá', 'code' => '11001', 'is_capital' => true],
            ],
            
            // Valle del Cauca
            'Valle del Cauca' => [
                ['name' => 'Cali', 'code' => '76001', 'is_capital' => true],
                ['name' => 'Palmira', 'code' => '76520'],
                ['name' => 'Buenaventura', 'code' => '76109'],
                ['name' => 'Tuluá', 'code' => '76834'],
            ],
            
            // Atlántico
            'Atlántico' => [
                ['name' => 'Barranquilla', 'code' => '08001', 'is_capital' => true],
                ['name' => 'Soledad', 'code' => '08758'],
                ['name' => 'Malambo', 'code' => '08433'],
            ],
            
            // Santander
            'Santander' => [
                ['name' => 'Bucaramanga', 'code' => '68001', 'is_capital' => true],
                ['name' => 'Floridablanca', 'code' => '68276'],
                ['name' => 'Girón', 'code' => '68307'],
                ['name' => 'Piedecuesta', 'code' => '68547'],
            ],
            
            // Cundinamarca
            'Cundinamarca' => [
                ['name' => 'Fusagasugá', 'code' => '25290'],
                ['name' => 'Chía', 'code' => '25175'],
                ['name' => 'Zipaquirá', 'code' => '25899'],
                ['name' => 'Soacha', 'code' => '25754'],
                ['name' => 'Facatativá', 'code' => '25269'],
            ],
            
            // Bolívar
            'Bolívar' => [
                ['name' => 'Cartagena', 'code' => '13001', 'is_capital' => true],
                ['name' => 'Magangué', 'code' => '13430'],
            ],
            
            // Norte de Santander
            'Norte de Santander' => [
                ['name' => 'Cúcuta', 'code' => '54001', 'is_capital' => true],
                ['name' => 'Ocaña', 'code' => '54498'],
            ],
            
            // Córdoba
            'Córdoba' => [
                ['name' => 'Montería', 'code' => '23001', 'is_capital' => true],
            ],
            
            // Tolima
            'Tolima' => [
                ['name' => 'Ibagué', 'code' => '73001', 'is_capital' => true],
            ],
            
            // Huila
            'Huila' => [
                ['name' => 'Neiva', 'code' => '41001', 'is_capital' => true],
            ],
            
            // Meta
            'Meta' => [
                ['name' => 'Villavicencio', 'code' => '50001', 'is_capital' => true],
            ],
            
            // Nariño
            'Nariño' => [
                ['name' => 'Pasto', 'code' => '52001', 'is_capital' => true],
                ['name' => 'Tumaco', 'code' => '52835'],
            ],
            
            // Caldas
            'Caldas' => [
                ['name' => 'Manizales', 'code' => '17001', 'is_capital' => true],
            ],
            
            // Quindío
            'Quindío' => [
                ['name' => 'Armenia', 'code' => '63001', 'is_capital' => true],
            ],
            
            // Risaralda
            'Risaralda' => [
                ['name' => 'Pereira', 'code' => '66001', 'is_capital' => true],
                ['name' => 'Dosquebradas', 'code' => '66170'],
            ],
            
            // Cesar
            'Cesar' => [
                ['name' => 'Valledupar', 'code' => '20001', 'is_capital' => true],
            ],
            
            // Magdalena
            'Magdalena' => [
                ['name' => 'Santa Marta', 'code' => '47001', 'is_capital' => true],
            ],
            
            // La Guajira
            'La Guajira' => [
                ['name' => 'Riohacha', 'code' => '44001', 'is_capital' => true],
            ],
            
            // Sucre
            'Sucre' => [
                ['name' => 'Sincelejo', 'code' => '70001', 'is_capital' => true],
            ],
            
            // Cauca
            'Cauca' => [
                ['name' => 'Popayán', 'code' => '19001', 'is_capital' => true],
            ],
            
            // Boyacá
            'Boyacá' => [
                ['name' => 'Tunja', 'code' => '15001', 'is_capital' => true],
                ['name' => 'Duitama', 'code' => '15238'],
                ['name' => 'Sogamoso', 'code' => '15759'],
            ],
            
            // Casanare
            'Casanare' => [
                ['name' => 'Yopal', 'code' => '85001', 'is_capital' => true],
            ],
            
            // Arauca
            'Arauca' => [
                ['name' => 'Arauca', 'code' => '81001', 'is_capital' => true],
            ],
            
            // Putumayo
            'Putumayo' => [
                ['name' => 'Mocoa', 'code' => '86001', 'is_capital' => true],
            ],
            
            // Caquetá
            'Caquetá' => [
                ['name' => 'Florencia', 'code' => '18001', 'is_capital' => true],
            ],
            
            // Chocó
            'Chocó' => [
                ['name' => 'Quibdó', 'code' => '27001', 'is_capital' => true],
            ],
            
            // San Andrés y Providencia
            'San Andrés y Providencia' => [
                ['name' => 'San Andrés', 'code' => '88001', 'is_capital' => true],
            ],
            
            // Amazonas
            'Amazonas' => [
                ['name' => 'Leticia', 'code' => '91001', 'is_capital' => true],
            ],
            
            // Guainía
            'Guainía' => [
                ['name' => 'Inírida', 'code' => '94001', 'is_capital' => true],
            ],
            
            // Guaviare
            'Guaviare' => [
                ['name' => 'San José del Guaviare', 'code' => '95001', 'is_capital' => true],
            ],
            
            // Vaupés
            'Vaupés' => [
                ['name' => 'Mitú', 'code' => '97001', 'is_capital' => true],
            ],
            
            // Vichada
            'Vichada' => [
                ['name' => 'Puerto Carreño', 'code' => '99001', 'is_capital' => true],
            ],
        ];

        foreach ($citiesData as $stateName => $cities) {
            $state = State::where('country_id', $colombia->id)
                          ->where('name', $stateName)
                          ->first();
            
            if (!$state) {
                $this->command->warn("No se encontró el departamento: {$stateName}");
                continue;
            }

            foreach ($cities as $cityData) {
                City::firstOrCreate(
                    [
                        'state_id' => $state->id,
                        'code' => $cityData['code']
                    ],
                    [
                        'state_id' => $state->id,
                        'name' => $cityData['name'],
                        'code' => $cityData['code'],
                        'is_capital' => $cityData['is_capital'] ?? false,
                        'active' => true
                    ]
                );
            }
        }

        $this->command->info('Ciudades principales de Colombia creadas exitosamente.');
    }
}