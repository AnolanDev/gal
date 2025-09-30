<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EstudianteUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'docente']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $estudianteId = $this->route('estudiante')->id;

        return [
            // ==========================================
            // INFORMACIÓN PERSONAL
            // ==========================================
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'tipo_documento' => 'required|in:registro_civil,tarjeta_identidad,cedula,pasaporte',
            'documento_identidad' => [
                'required',
                'string',
                'max:50',
                Rule::unique('estudiantes')->ignore($estudianteId)
            ],
            'genero' => 'required|in:masculino,femenino',
            'fecha_nacimiento' => 'required|date|before:today',

            // Geografía (lugar de nacimiento)
            'birth_country_id' => 'required|exists:countries,id',
            'birth_state_id' => 'required|exists:states,id',
            'birth_city_id' => 'required|exists:cities,id',

            // ==========================================
            // INFORMACIÓN DE CONTACTO
            // ==========================================
            'direccion' => 'required|string',
            'telefono' => 'nullable|string|max:20',
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('estudiantes')->ignore($estudianteId)
            ],
            
            // Contacto de emergencia
            'contacto_emergencia_nombre' => 'required|string|max:100',
            'contacto_emergencia_telefono' => 'required|string|max:20',
            'contacto_emergencia_relacion' => 'required|string|max:50',

            // ==========================================
            // INFORMACIÓN DE PADRES
            // ==========================================
            
            // PADRE (al menos uno de los dos padres requerido)
            'padre_nombres' => 'required_without_all:madre_nombres|nullable|string|max:100',
            'padre_apellidos' => 'required_with:padre_nombres|nullable|string|max:100',
            'padre_tipo_documento' => 'required_with:padre_nombres|nullable|string|max:20',
            'padre_documento' => 'required_with:padre_nombres|nullable|string|max:50',
            'padre_telefono' => 'required_with:padre_nombres|nullable|string|max:20',
            'padre_email' => 'nullable|email|max:100',
            'padre_ocupacion' => 'nullable|string|max:100',
            'padre_lugar_trabajo' => 'nullable|string|max:150',
            'padre_autorizado_recoger' => 'boolean',

            // MADRE (al menos uno de los dos padres requerido)
            'madre_nombres' => 'required_without_all:padre_nombres|nullable|string|max:100',
            'madre_apellidos' => 'required_with:madre_nombres|nullable|string|max:100',
            'madre_tipo_documento' => 'required_with:madre_nombres|nullable|string|max:20',
            'madre_documento' => 'required_with:madre_nombres|nullable|string|max:50',
            'madre_telefono' => 'required_with:madre_nombres|nullable|string|max:20',
            'madre_email' => 'nullable|email|max:100',
            'madre_ocupacion' => 'nullable|string|max:100',
            'madre_lugar_trabajo' => 'nullable|string|max:150',
            'madre_autorizada_recoger' => 'boolean',

            // ACUDIENTE ADICIONAL
            'tiene_acudiente_adicional' => 'boolean',
            'acudiente_nombres' => 'required_if:tiene_acudiente_adicional,true|nullable|string|max:100',
            'acudiente_apellidos' => 'required_with:acudiente_nombres|nullable|string|max:100',
            'acudiente_tipo_documento' => 'required_with:acudiente_nombres|nullable|string|max:20',
            'acudiente_documento' => 'required_with:acudiente_nombres|nullable|string|max:50',
            'acudiente_parentesco' => 'required_with:acudiente_nombres|nullable|string|max:50',
            'acudiente_telefono' => 'required_with:acudiente_nombres|nullable|string|max:20',
            'acudiente_email' => 'nullable|email|max:100',

            // ==========================================
            // INFORMACIÓN ACADÉMICA
            // ==========================================
            'grado_id' => 'required|exists:grados,id',
            'fecha_ingreso' => 'required|date',
            'codigo_estudiante' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('estudiantes')->ignore($estudianteId)
            ],
            'estado' => 'required|in:activo,inactivo,retirado',

            // Antecedentes académicos
            'es_estudiante_nuevo' => 'boolean',
            'colegio_procedencia' => 'required_if:es_estudiante_nuevo,false|nullable|string|max:150',
            'ultimo_grado_cursado' => 'required_if:es_estudiante_nuevo,false|nullable|string|max:50',
            'ano_finalizacion' => 'required_if:es_estudiante_nuevo,false|nullable|integer|min:2000|max:' . date('Y'),
            'tiene_certificados_pendientes' => 'boolean',
            'observaciones_academicas' => 'nullable|string',

            // ==========================================
            // INFORMACIÓN MÉDICA
            // ==========================================
            'tipo_sangre' => 'required|in:O+,O-,A+,A-,B+,B-,AB+,AB-',
            'eps' => 'required|string|max:100',
            'numero_afiliacion_eps' => 'nullable|string|max:50',
            'alergias' => 'nullable|string',
            'medicamentos' => 'nullable|string',
            'condiciones_medicas' => 'nullable|string',
            'restricciones_fisicas' => 'nullable|string',
            
            // Archivos
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Observaciones generales
            'observaciones' => 'nullable|string',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            // Información personal
            'nombres.required' => 'Los nombres son obligatorios.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'documento_identidad.required' => 'El documento de identidad es obligatorio.',
            'documento_identidad.unique' => 'Ya existe otro estudiante con este documento de identidad.',
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'genero.required' => 'El género es obligatorio.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',

            // Geografía
            'birth_country_id.required' => 'El país de nacimiento es obligatorio.',
            'birth_state_id.required' => 'El departamento de nacimiento es obligatorio.',
            'birth_city_id.required' => 'La ciudad de nacimiento es obligatoria.',

            // Contacto
            'direccion.required' => 'La dirección es obligatoria.',
            'email.unique' => 'Ya existe otro estudiante con este email.',
            'contacto_emergencia_nombre.required' => 'El nombre del contacto de emergencia es obligatorio.',
            'contacto_emergencia_telefono.required' => 'El teléfono del contacto de emergencia es obligatorio.',

            // Padres
            'padre_nombres.required_without_all' => 'Debe registrar información del padre o de la madre.',
            'madre_nombres.required_without_all' => 'Debe registrar información del padre o de la madre.',

            // Académico
            'grado_id.required' => 'El grado es obligatorio.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'codigo_estudiante.unique' => 'Ya existe otro estudiante con este código.',
            'estado.required' => 'El estado es obligatorio.',

            // Médico
            'tipo_sangre.required' => 'El tipo de sangre es obligatorio.',
            'eps.required' => 'La EPS es obligatoria.',

            // Archivos
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La foto debe ser un archivo JPEG, PNG o JPG.',
            'foto.max' => 'La foto no puede superar los 2MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Aplicar las mismas transformaciones que en StoreRequest
        $cleanData = [];

        // Limpiar documento de identidad
        if ($this->has('documento_identidad')) {
            $cleanData['documento_identidad'] = preg_replace('/[^0-9A-Za-z]/', '', $this->documento_identidad);
        }

        // Limpiar teléfonos
        $phoneFields = [
            'telefono', 
            'contacto_emergencia_telefono', 
            'padre_telefono', 
            'madre_telefono', 
            'acudiente_telefono'
        ];
        
        foreach ($phoneFields as $field) {
            if ($this->has($field)) {
                $cleanData[$field] = preg_replace('/[^0-9+\-\s()]/', '', $this->$field);
            }
        }

        // Capitalizar nombres
        $nameFields = [
            'nombres', 'apellidos', 
            'padre_nombres', 'padre_apellidos',
            'madre_nombres', 'madre_apellidos',
            'acudiente_nombres', 'acudiente_apellidos',
            'contacto_emergencia_nombre'
        ];
        
        foreach ($nameFields as $field) {
            if ($this->has($field)) {
                $cleanData[$field] = ucwords(strtolower(trim($this->$field)));
            }
        }

        // Convertir booleanos
        $booleanFields = [
            'padre_autorizado_recoger',
            'madre_autorizada_recoger', 
            'tiene_acudiente_adicional',
            'es_estudiante_nuevo',
            'tiene_certificados_pendientes'
        ];
        
        foreach ($booleanFields as $field) {
            if ($this->has($field)) {
                $cleanData[$field] = filter_var($this->$field, FILTER_VALIDATE_BOOLEAN);
            }
        }

        $this->merge($cleanData);
    }
}