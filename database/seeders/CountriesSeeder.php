<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $countries = [
            [
                'name' => 'Colombia',
                'code' => 'COL',
                'phone_code' => '+57',
                'active' => true
            ],
            [
                'name' => 'Venezuela',
                'code' => 'VEN',
                'phone_code' => '+58',
                'active' => true
            ],
            [
                'name' => 'Ecuador',
                'code' => 'ECU',
                'phone_code' => '+593',
                'active' => true
            ],
            [
                'name' => 'Perú',
                'code' => 'PER',
                'phone_code' => '+51',
                'active' => true
            ],
            [
                'name' => 'Brasil',
                'code' => 'BRA',
                'phone_code' => '+55',
                'active' => true
            ],
            [
                'name' => 'Argentina',
                'code' => 'ARG',
                'phone_code' => '+54',
                'active' => true
            ],
            [
                'name' => 'Chile',
                'code' => 'CHL',
                'phone_code' => '+56',
                'active' => true
            ],
            [
                'name' => 'Bolivia',
                'code' => 'BOL',
                'phone_code' => '+591',
                'active' => true
            ],
            [
                'name' => 'Paraguay',
                'code' => 'PRY',
                'phone_code' => '+595',
                'active' => true
            ],
            [
                'name' => 'Uruguay',
                'code' => 'URY',
                'phone_code' => '+598',
                'active' => true
            ],
            [
                'name' => 'México',
                'code' => 'MEX',
                'phone_code' => '+52',
                'active' => true
            ],
            [
                'name' => 'Estados Unidos',
                'code' => 'USA',
                'phone_code' => '+1',
                'active' => true
            ],
            [
                'name' => 'Canadá',
                'code' => 'CAN',
                'phone_code' => '+1',
                'active' => true
            ],
            [
                'name' => 'Panamá',
                'code' => 'PAN',
                'phone_code' => '+507',
                'active' => true
            ],
            [
                'name' => 'Costa Rica',
                'code' => 'CRI',
                'phone_code' => '+506',
                'active' => true
            ],
            [
                'name' => 'España',
                'code' => 'ESP',
                'phone_code' => '+34',
                'active' => true
            ],
            [
                'name' => 'Francia',
                'code' => 'FRA',
                'phone_code' => '+33',
                'active' => true
            ],
            [
                'name' => 'Italia',
                'code' => 'ITA',
                'phone_code' => '+39',
                'active' => true
            ],
            [
                'name' => 'Alemania',
                'code' => 'DEU',
                'phone_code' => '+49',
                'active' => true
            ],
            [
                'name' => 'Reino Unido',
                'code' => 'GBR',
                'phone_code' => '+44',
                'active' => true
            ]
        ];

        foreach ($countries as $countryData) {
            Country::firstOrCreate(
                ['code' => $countryData['code']],
                $countryData
            );
        }

        $this->command->info('Países creados exitosamente.');
    }
}