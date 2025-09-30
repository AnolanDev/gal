<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;

class GeografiaController extends Controller
{
    /**
     * Obtener todos los países activos
     */
    public function getCountries()
    {
        try {
            $countries = Country::active()
                ->orderedByName()
                ->select('id', 'name', 'code', 'phone_code')
                ->get();

            return response()->json($countries);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar países',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estados/departamentos por país
     */
    public function getStates(Country $country)
    {
        try {
            $states = $country->states()
                ->active()
                ->orderedByName()
                ->select('id', 'name', 'code')
                ->get();

            return response()->json($states);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar estados',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estados por ID de país (alternativo)
     */
    public function getStatesByCountryId($countryId)
    {
        try {
            $states = State::byCountry($countryId)
                ->active()
                ->orderedByName()
                ->select('id', 'name', 'code')
                ->get();

            return response()->json($states);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar estados',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener ciudades por estado
     */
    public function getCities(State $state)
    {
        try {
            $cities = $state->cities()
                ->active()
                ->orderedByCapitalFirst()
                ->select('id', 'name', 'code', 'is_capital')
                ->get();

            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar ciudades',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener ciudades por ID de estado (alternativo)
     */
    public function getCitiesByStateId($stateId)
    {
        try {
            $cities = City::byState($stateId)
                ->active()
                ->orderedByCapitalFirst()
                ->select('id', 'name', 'code', 'is_capital')
                ->get();

            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar ciudades',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Búsqueda de lugares (para autocompletado)
     */
    public function searchPlaces(Request $request)
    {
        $term = $request->get('q', '');
        
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        try {
            $results = [];

            // Buscar países
            $countries = Country::active()
                ->where('name', 'like', "%{$term}%")
                ->select('id', 'name', 'code')
                ->limit(5)
                ->get()
                ->map(function ($country) {
                    return [
                        'id' => $country->id,
                        'name' => $country->name,
                        'type' => 'country',
                        'full_path' => $country->name
                    ];
                });

            // Buscar estados
            $states = State::active()
                ->with('country:id,name')
                ->where('name', 'like', "%{$term}%")
                ->select('id', 'name', 'country_id')
                ->limit(5)
                ->get()
                ->map(function ($state) {
                    return [
                        'id' => $state->id,
                        'name' => $state->name,
                        'type' => 'state',
                        'full_path' => $state->name . ', ' . $state->country->name
                    ];
                });

            // Buscar ciudades
            $cities = City::active()
                ->with(['state:id,name', 'state.country:id,name'])
                ->where('name', 'like', "%{$term}%")
                ->select('id', 'name', 'state_id', 'is_capital')
                ->limit(5)
                ->get()
                ->map(function ($city) {
                    return [
                        'id' => $city->id,
                        'name' => $city->name,
                        'type' => 'city',
                        'is_capital' => $city->is_capital,
                        'full_path' => $city->name . ', ' . $city->state->name . ', ' . $city->state->country->name
                    ];
                });

            $results = collect()
                ->concat($countries)
                ->concat($states)
                ->concat($cities)
                ->sortBy('name')
                ->values();

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error en la búsqueda',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información completa de ubicación por ciudad
     */
    public function getFullLocation($cityId)
    {
        try {
            $city = City::with(['state.country'])
                ->where('id', $cityId)
                ->first();

            if (!$city) {
                return response()->json(['error' => 'Ciudad no encontrada'], 404);
            }

            return response()->json([
                'city' => [
                    'id' => $city->id,
                    'name' => $city->name,
                    'code' => $city->code,
                    'is_capital' => $city->is_capital
                ],
                'state' => [
                    'id' => $city->state->id,
                    'name' => $city->state->name,
                    'code' => $city->state->code
                ],
                'country' => [
                    'id' => $city->state->country->id,
                    'name' => $city->state->country->name,
                    'code' => $city->state->country->code,
                    'phone_code' => $city->state->country->phone_code
                ],
                'full_path' => $city->name . ', ' . $city->state->name . ', ' . $city->state->country->name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener ubicación',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}