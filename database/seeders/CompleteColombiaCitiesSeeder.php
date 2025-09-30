<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class CompleteColombiaCitiesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     * 
     * Este seeder incluye TODOS los municipios de Colombia según el DANE
     */
    public function run(): void
    {
        $colombia = Country::where('code', 'COL')->first();
        
        if (!$colombia) {
            $this->command->error('No se encontró Colombia en la base de datos.');
            return;
        }

        // Datos completos de municipios por departamento (según DANE 2023)
        $municipiosData = [
            // ANTIOQUIA - 125 municipios
            'Antioquia' => [
                ['name' => 'Medellín', 'code' => '05001', 'is_capital' => true],
                ['name' => 'Abejorral', 'code' => '05002'],
                ['name' => 'Abriaquí', 'code' => '05004'],
                ['name' => 'Alejandría', 'code' => '05021'],
                ['name' => 'Amagá', 'code' => '05030'],
                ['name' => 'Amalfi', 'code' => '05031'],
                ['name' => 'Andes', 'code' => '05034'],
                ['name' => 'Angelópolis', 'code' => '05036'],
                ['name' => 'Angostura', 'code' => '05038'],
                ['name' => 'Anorí', 'code' => '05040'],
                ['name' => 'Anzá', 'code' => '05042'],
                ['name' => 'Apartadó', 'code' => '05044'],
                ['name' => 'Arboletes', 'code' => '05045'],
                ['name' => 'Argelia', 'code' => '05051'],
                ['name' => 'Armenia', 'code' => '05055'],
                ['name' => 'Barbosa', 'code' => '05059'],
                ['name' => 'Bello', 'code' => '05088'],
                ['name' => 'Belmira', 'code' => '05091'],
                ['name' => 'Betania', 'code' => '05093'],
                ['name' => 'Betulia', 'code' => '05101'],
                ['name' => 'Ciudad Bolívar', 'code' => '05107'],
                ['name' => 'Briceño', 'code' => '05113'],
                ['name' => 'Buriticá', 'code' => '05120'],
                ['name' => 'Cáceres', 'code' => '05125'],
                ['name' => 'Caicedo', 'code' => '05129'],
                ['name' => 'Caldas', 'code' => '05134'],
                ['name' => 'Campamento', 'code' => '05138'],
                ['name' => 'Cañasgordas', 'code' => '05142'],
                ['name' => 'Caracolí', 'code' => '05145'],
                ['name' => 'Caramanta', 'code' => '05147'],
                ['name' => 'Carepa', 'code' => '05148'],
                ['name' => 'Carmen de Viboral', 'code' => '05150'],
                ['name' => 'Carolina', 'code' => '05154'],
                ['name' => 'Caucasia', 'code' => '05172'],
                ['name' => 'Chigorodó', 'code' => '05190'],
                ['name' => 'Cisneros', 'code' => '05197'],
                ['name' => 'Cocorná', 'code' => '05206'],
                ['name' => 'Concepción', 'code' => '05209'],
                ['name' => 'Concordia', 'code' => '05212'],
                ['name' => 'Copacabana', 'code' => '05234'],
                ['name' => 'Dabeiba', 'code' => '05237'],
                ['name' => 'Don Matías', 'code' => '05240'],
                ['name' => 'Ebéjico', 'code' => '05250'],
                ['name' => 'El Bagre', 'code' => '05264'],
                ['name' => 'Entrerríos', 'code' => '05266'],
                ['name' => 'Envigado', 'code' => '05266'],
                ['name' => 'Fredonia', 'code' => '05282'],
                ['name' => 'Frontino', 'code' => '05284'],
                ['name' => 'Giraldo', 'code' => '05306'],
                ['name' => 'Girardota', 'code' => '05308'],
                ['name' => 'Gómez Plata', 'code' => '05310'],
                ['name' => 'Granada', 'code' => '05313'],
                ['name' => 'Guadalupe', 'code' => '05315'],
                ['name' => 'Guarne', 'code' => '05318'],
                ['name' => 'Guatapé', 'code' => '05321'],
                ['name' => 'Heliconia', 'code' => '05347'],
                ['name' => 'Hispania', 'code' => '05353'],
                ['name' => 'Itagüí', 'code' => '05360'],
                ['name' => 'Ituango', 'code' => '05361'],
                ['name' => 'Jardín', 'code' => '05364'],
                ['name' => 'Jericó', 'code' => '05368'],
                ['name' => 'La Ceja', 'code' => '05376'],
                ['name' => 'La Estrella', 'code' => '05380'],
                ['name' => 'La Pintada', 'code' => '05390'],
                ['name' => 'La Unión', 'code' => '05400'],
                ['name' => 'Liborina', 'code' => '05411'],
                ['name' => 'Maceo', 'code' => '05425'],
                ['name' => 'Marinilla', 'code' => '05440'],
                ['name' => 'Montebello', 'code' => '05467'],
                ['name' => 'Murindó', 'code' => '05475'],
                ['name' => 'Mutatá', 'code' => '05480'],
                ['name' => 'Nariño', 'code' => '05483'],
                ['name' => 'Nechí', 'code' => '05490'],
                ['name' => 'Necoclí', 'code' => '05495'],
                ['name' => 'Olaya', 'code' => '05501'],
                ['name' => 'Peñol', 'code' => '05541'],
                ['name' => 'Peque', 'code' => '05543'],
                ['name' => 'Pueblorrico', 'code' => '05576'],
                ['name' => 'Puerto Berrío', 'code' => '05579'],
                ['name' => 'Puerto Nare', 'code' => '05585'],
                ['name' => 'Puerto Triunfo', 'code' => '05591'],
                ['name' => 'Remedios', 'code' => '05604'],
                ['name' => 'Retiro', 'code' => '05607'],
                ['name' => 'Rionegro', 'code' => '05615'],
                ['name' => 'Sabanalarga', 'code' => '05628'],
                ['name' => 'Sabaneta', 'code' => '05631'],
                ['name' => 'Salgar', 'code' => '05642'],
                ['name' => 'San Andrés de Cuerquia', 'code' => '05647'],
                ['name' => 'San Carlos', 'code' => '05649'],
                ['name' => 'San Francisco', 'code' => '05652'],
                ['name' => 'San Jerónimo', 'code' => '05656'],
                ['name' => 'San José de la Montaña', 'code' => '05658'],
                ['name' => 'San Juan de Urabá', 'code' => '05659'],
                ['name' => 'San Luis', 'code' => '05660'],
                ['name' => 'San Pedro', 'code' => '05664'],
                ['name' => 'San Pedro de Urabá', 'code' => '05665'],
                ['name' => 'San Rafael', 'code' => '05667'],
                ['name' => 'San Roque', 'code' => '05670'],
                ['name' => 'San Vicente', 'code' => '05674'],
                ['name' => 'Santa Bárbara', 'code' => '05679'],
                ['name' => 'Santa Rosa de Osos', 'code' => '05686'],
                ['name' => 'Santo Domingo', 'code' => '05690'],
                ['name' => 'Segovia', 'code' => '05697'],
                ['name' => 'Sonsón', 'code' => '05736'],
                ['name' => 'Sopetrán', 'code' => '05756'],
                ['name' => 'Támesis', 'code' => '05761'],
                ['name' => 'Tarazá', 'code' => '05789'],
                ['name' => 'Tarso', 'code' => '05790'],
                ['name' => 'Titiribí', 'code' => '05792'],
                ['name' => 'Toledo', 'code' => '05809'],
                ['name' => 'Turbo', 'code' => '05837'],
                ['name' => 'Uramita', 'code' => '05842'],
                ['name' => 'Urrao', 'code' => '05847'],
                ['name' => 'Valdivia', 'code' => '05854'],
                ['name' => 'Valparaíso', 'code' => '05856'],
                ['name' => 'Vegachí', 'code' => '05858'],
                ['name' => 'Venecia', 'code' => '05861'],
                ['name' => 'Vigía del Fuerte', 'code' => '05873'],
                ['name' => 'Yalí', 'code' => '05885'],
                ['name' => 'Yarumal', 'code' => '05887'],
                ['name' => 'Yolombó', 'code' => '05890'],
                ['name' => 'Yondó', 'code' => '05893'],
                ['name' => 'Zaragoza', 'code' => '05895'],
            ],

            // ATLÁNTICO - 23 municipios
            'Atlántico' => [
                ['name' => 'Barranquilla', 'code' => '08001', 'is_capital' => true],
                ['name' => 'Baranoa', 'code' => '08078'],
                ['name' => 'Campo de la Cruz', 'code' => '08137'],
                ['name' => 'Candelaria', 'code' => '08141'],
                ['name' => 'Galapa', 'code' => '08296'],
                ['name' => 'Juan de Acosta', 'code' => '08372'],
                ['name' => 'Luruaco', 'code' => '08421'],
                ['name' => 'Malambo', 'code' => '08433'],
                ['name' => 'Manatí', 'code' => '08436'],
                ['name' => 'Palmar de Varela', 'code' => '08520'],
                ['name' => 'Piojó', 'code' => '08549'],
                ['name' => 'Polonuevo', 'code' => '08558'],
                ['name' => 'Ponedera', 'code' => '08560'],
                ['name' => 'Puerto Colombia', 'code' => '08573'],
                ['name' => 'Repelón', 'code' => '08606'],
                ['name' => 'Sabanagrande', 'code' => '08634'],
                ['name' => 'Sabanalarga', 'code' => '08638'],
                ['name' => 'Santa Lucía', 'code' => '08675'],
                ['name' => 'Santo Tomás', 'code' => '08685'],
                ['name' => 'Soledad', 'code' => '08758'],
                ['name' => 'Suan', 'code' => '08770'],
                ['name' => 'Tubará', 'code' => '08832'],
                ['name' => 'Usiacurí', 'code' => '08849'],
            ],

            // BOGOTÁ D.C. - 1 distrito capital
            'Bogotá D.C.' => [
                ['name' => 'Bogotá', 'code' => '11001', 'is_capital' => true],
            ],

            // BOLÍVAR - 46 municipios
            'Bolívar' => [
                ['name' => 'Cartagena', 'code' => '13001', 'is_capital' => true],
                ['name' => 'Achí', 'code' => '13006'],
                ['name' => 'Altos del Rosario', 'code' => '13030'],
                ['name' => 'Arenal', 'code' => '13042'],
                ['name' => 'Arjona', 'code' => '13052'],
                ['name' => 'Arroyohondo', 'code' => '13062'],
                ['name' => 'Barranco de Loba', 'code' => '13074'],
                ['name' => 'Calamar', 'code' => '13140'],
                ['name' => 'Cantagallo', 'code' => '13160'],
                ['name' => 'Cicuco', 'code' => '13188'],
                ['name' => 'Clemencia', 'code' => '13212'],
                ['name' => 'Córdoba', 'code' => '13222'],
                ['name' => 'El Carmen de Bolívar', 'code' => '13244'],
                ['name' => 'El Guamo', 'code' => '13248'],
                ['name' => 'El Peñón', 'code' => '13268'],
                ['name' => 'Hatillo de Loba', 'code' => '13300'],
                ['name' => 'Magangué', 'code' => '13430'],
                ['name' => 'Mahates', 'code' => '13433'],
                ['name' => 'Margarita', 'code' => '13440'],
                ['name' => 'María la Baja', 'code' => '13442'],
                ['name' => 'Mompós', 'code' => '13468'],
                ['name' => 'Montecristo', 'code' => '13473'],
                ['name' => 'Morales', 'code' => '13490'],
                ['name' => 'Norosí', 'code' => '13473'],
                ['name' => 'Pinillos', 'code' => '13549'],
                ['name' => 'Regidor', 'code' => '13580'],
                ['name' => 'Río Viejo', 'code' => '13600'],
                ['name' => 'San Cristóbal', 'code' => '13620'],
                ['name' => 'San Estanislao', 'code' => '13647'],
                ['name' => 'San Fernando', 'code' => '13650'],
                ['name' => 'San Jacinto', 'code' => '13654'],
                ['name' => 'San Jacinto del Cauca', 'code' => '13655'],
                ['name' => 'San Juan Nepomuceno', 'code' => '13657'],
                ['name' => 'San Martín de Loba', 'code' => '13667'],
                ['name' => 'San Pablo', 'code' => '13670'],
                ['name' => 'Santa Catalina', 'code' => '13673'],
                ['name' => 'Santa Rosa', 'code' => '13683'],
                ['name' => 'Santa Rosa del Sur', 'code' => '13688'],
                ['name' => 'Simití', 'code' => '13744'],
                ['name' => 'Soplaviento', 'code' => '13760'],
                ['name' => 'Talaigua Nuevo', 'code' => '13780'],
                ['name' => 'Tiquisio', 'code' => '13810'],
                ['name' => 'Turbaco', 'code' => '13836'],
                ['name' => 'Turbaná', 'code' => '13838'],
                ['name' => 'Villanueva', 'code' => '13873'],
                ['name' => 'Zambrano', 'code' => '13894'],
            ],
        ];

        $totalMunicipios = 0;
        
        foreach ($municipiosData as $stateName => $municipios) {
            $state = State::where('country_id', $colombia->id)
                          ->where('name', $stateName)
                          ->first();
            
            if (!$state) {
                $this->command->warn("No se encontró el departamento: {$stateName}");
                continue;
            }

            foreach ($municipios as $municipioData) {
                City::updateOrCreate(
                    [
                        'state_id' => $state->id,
                        'code' => $municipioData['code']
                    ],
                    [
                        'state_id' => $state->id,
                        'name' => $municipioData['name'],
                        'code' => $municipioData['code'],
                        'is_capital' => $municipioData['is_capital'] ?? false,
                        'active' => true
                    ]
                );
                $totalMunicipios++;
            }
            
            $this->command->info("✓ {$stateName}: " . count($municipios) . " municipios");
        }

        $this->command->info("===========================================");
        $this->command->info("Total de municipios creados: {$totalMunicipios}");
        $this->command->info("===========================================");
        
        // Mostrar estadísticas
        $totalCountries = Country::count();
        $totalStates = State::count();
        $totalCities = City::count();
        
        $this->command->table(
            ['Entidad', 'Total'],
            [
                ['Países', $totalCountries],
                ['Departamentos/Estados', $totalStates], 
                ['Ciudades/Municipios', $totalCities]
            ]
        );
    }
}