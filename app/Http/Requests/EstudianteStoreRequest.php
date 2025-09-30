<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstudianteStoreRequest extends FormRequest
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
        return [
            // ==========================================
            // PASO 1: INFORMACIÓN PERSONAL
            // ==========================================
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'tipo_documento' => 'required|in:registro_civil,tarjeta_identidad,cedula,pasaporte',
            'documento_identidad' => 'required|string|max:50|unique:estudiantes,documento_identidad',
            'genero' => 'required|in:masculino,femenino',
            'fecha_nacimiento' => 'required|date|before:today',

            // Geografía (lugar de nacimiento)
            'birth_country_id' => 'required|exists:countries,id',
            'birth_state_id' => 'required|exists:states,id',
            'birth_city_id' => 'required|exists:cities,id',

            // ==========================================
            // PASO 2: INFORMACIÓN DE CONTACTO
            // ==========================================
            'direccion' => 'required|string',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:estudiantes,email',
            
            // Contacto de emergencia (requerido)
            'contacto_emergencia_nombre' => 'required|string|max:100',
            'contacto_emergencia_telefono' => 'required|string|max:20',
            'contacto_emergencia_relacion' => 'required|string|max:50',

            // ==========================================
            // PASO 3: INFORMACIÓN DE PADRES
            // ==========================================
            
            // PADRE (requerido - al menos uno de los dos padres)
            'padre_nombres' => 'required_without_all:madre_nombres|nullable|string|max:100',
            'padre_apellidos' => 'required_with:padre_nombres|nullable|string|max:100',
            'padre_tipo_documento' => 'required_with:padre_nombres|nullable|string|max:20',
            'padre_documento' => 'required_with:padre_nombres|nullable|string|max:50',
            'padre_telefono' => 'required_with:padre_nombres|nullable|string|max:20',
            'padre_email' => 'nullable|email|max:100',
            'padre_ocupacion' => 'nullable|string|max:100',
            'padre_lugar_trabajo' => 'nullable|string|max:150',
            'padre_autorizado_recoger' => 'boolean',

            // MADRE (requerida - al menos uno de los dos padres)
            'madre_nombres' => 'required_without_all:padre_nombres|nullable|string|max:100',
            'madre_apellidos' => 'required_with:madre_nombres|nullable|string|max:100',
            'madre_tipo_documento' => 'required_with:madre_nombres|nullable|string|max:20',
            'madre_documento' => 'required_with:madre_nombres|nullable|string|max:50',
            'madre_telefono' => 'required_with:madre_nombres|nullable|string|max:20',
            'madre_email' => 'nullable|email|max:100',
            'madre_ocupacion' => 'nullable|string|max:100',
            'madre_lugar_trabajo' => 'nullable|string|max:150',
            'madre_autorizada_recoger' => 'boolean',

            // ACUDIENTE ADICIONAL (condicional)
            'tiene_acudiente_adicional' => 'boolean',
            'acudiente_nombres' => 'required_if:tiene_acudiente_adicional,true|nullable|string|max:100',
            'acudiente_apellidos' => 'required_with:acudiente_nombres|nullable|string|max:100',
            'acudiente_tipo_documento' => 'required_with:acudiente_nombres|nullable|string|max:20',
            'acudiente_documento' => 'required_with:acudiente_nombres|nullable|string|max:50',
            'acudiente_parentesco' => 'required_with:acudiente_nombres|nullable|string|max:50',
            'acudiente_telefono' => 'required_with:acudiente_nombres|nullable|string|max:20',
            'acudiente_email' => 'nullable|email|max:100',

            // ==========================================
            // PASO 4: INFORMACIÓN ACADÉMICA
            // ==========================================
            'grado_id' => 'required|exists:grados,id',
            'fecha_ingreso' => 'required|date',
            'codigo_estudiante' => 'nullable|string|max:20|unique:estudiantes,codigo_estudiante',
            'estado' => 'nullable|in:activo,inactivo,retirado',

            // Antecedentes académicos (condicionales)
            'es_estudiante_nuevo' => 'boolean',
            'colegio_procedencia' => 'required_if:es_estudiante_nuevo,false|nullable|string|max:150',
            'ultimo_grado_cursado' => 'required_if:es_estudiante_nuevo,false|nullable|string|max:50',
            'ano_finalizacion' => 'required_if:es_estudiante_nuevo,false|nullable|integer|min:2000|max:' . date('Y'),
            'tiene_certificados_pendientes' => 'boolean',
            'observaciones_academicas' => 'nullable|string',

            // ==========================================
            // PASO 5: INFORMACIÓN MÉDICA
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
            'nombres.max' => 'Los nombres no pueden superar los 100 caracteres.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.max' => 'Los apellidos no pueden superar los 100 caracteres.',
            'documento_identidad.required' => 'El documento de identidad es obligatorio.',
            'documento_identidad.unique' => 'Ya existe un estudiante con este documento de identidad.',
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'tipo_documento.in' => 'El tipo de documento debe ser registro civil, tarjeta de identidad, cédula o pasaporte.',
            'genero.required' => 'El género es obligatorio.',
            'genero.in' => 'El género debe ser masculino o femenino.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',

            // Geografía
            'birth_country_id.required' => 'El país de nacimiento es obligatorio.',
            'birth_country_id.exists' => 'El país seleccionado no existe.',
            'birth_state_id.required' => 'El departamento de nacimiento es obligatorio.',
            'birth_state_id.exists' => 'El departamento seleccionado no existe.',
            'birth_city_id.required' => 'La ciudad de nacimiento es obligatoria.',
            'birth_city_id.exists' => 'La ciudad seleccionada no existe.',

            // Contacto
            'direccion.required' => 'La dirección es obligatoria.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Ya existe un estudiante con este email.',
            'contacto_emergencia_nombre.required' => 'El nombre del contacto de emergencia es obligatorio.',
            'contacto_emergencia_telefono.required' => 'El teléfono del contacto de emergencia es obligatorio.',
            'contacto_emergencia_relacion.required' => 'La relación del contacto de emergencia es obligatoria.',

            // Padres
            'padre_nombres.required_without_all' => 'Debe registrar información del padre o de la madre.',
            'madre_nombres.required_without_all' => 'Debe registrar información del padre o de la madre.',
            'padre_apellidos.required_with' => 'Los apellidos del padre son requeridos cuando se registran sus nombres.',
            'madre_apellidos.required_with' => 'Los apellidos de la madre son requeridos cuando se registran sus nombres.',
            'padre_documento.required_with' => 'El documento del padre es requerido.',
            'madre_documento.required_with' => 'El documento de la madre es requerido.',
            'padre_telefono.required_with' => 'El teléfono del padre es requerido.',
            'madre_telefono.required_with' => 'El teléfono de la madre es requerido.',

            // Acudiente
            'acudiente_nombres.required_if' => 'Los nombres del acudiente son requeridos cuando se marca que tiene acudiente adicional.',
            'acudiente_apellidos.required_with' => 'Los apellidos del acudiente son requeridos.',
            'acudiente_documento.required_with' => 'El documento del acudiente es requerido.',
            'acudiente_telefono.required_with' => 'El teléfono del acudiente es requerido.',
            'acudiente_parentesco.required_with' => 'El parentesco del acudiente es requerido.',

            // Académico
            'grado_id.required' => 'El grado es obligatorio.',
            'grado_id.exists' => 'El grado seleccionado no existe.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_ingreso.date' => 'La fecha de ingreso debe ser una fecha válida.',
            'codigo_estudiante.unique' => 'Ya existe un estudiante con este código.',
            'colegio_procedencia.required_if' => 'El colegio de procedencia es requerido para estudiantes no nuevos.',
            'ultimo_grado_cursado.required_if' => 'El último grado cursado es requerido para estudiantes no nuevos.',
            'ano_finalizacion.required_if' => 'El año de finalización es requerido para estudiantes no nuevos.',
            'ano_finalizacion.min' => 'El año debe ser mayor a 2000.',
            'ano_finalizacion.max' => 'El año no puede ser mayor al año actual.',

            // Médico
            'tipo_sangre.required' => 'El tipo de sangre es obligatorio.',
            'tipo_sangre.in' => 'El tipo de sangre debe ser uno de los tipos válidos.',
            'eps.required' => 'La EPS es obligatoria.',
            'eps.max' => 'El nombre de la EPS no puede superar los 100 caracteres.',

            // Archivos
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La foto debe ser un archivo JPEG, PNG o JPG.',
            'foto.max' => 'La foto no puede superar los 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nombres' => 'nombres',
            'apellidos' => 'apellidos',
            'documento_identidad' => 'documento de identidad',
            'tipo_documento' => 'tipo de documento',
            'genero' => 'género',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'birth_country_id' => 'país de nacimiento',
            'birth_state_id' => 'departamento de nacimiento',
            'birth_city_id' => 'ciudad de nacimiento',
            'direccion' => 'dirección',
            'telefono' => 'teléfono',
            'email' => 'email',
            'contacto_emergencia_nombre' => 'nombre del contacto de emergencia',
            'contacto_emergencia_telefono' => 'teléfono del contacto de emergencia',
            'contacto_emergencia_relacion' => 'relación del contacto de emergencia',
            'grado_id' => 'grado',
            'fecha_ingreso' => 'fecha de ingreso',
            'codigo_estudiante' => 'código de estudiante',
            'tipo_sangre' => 'tipo de sangre',
            'eps' => 'EPS',
            'foto' => 'foto',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Limpiar y formatear datos
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

        // Establecer valores por defecto
        if (!$this->has('estado')) {
            $cleanData['estado'] = 'activo';
        }
        
        if (!$this->has('es_estudiante_nuevo')) {
            $cleanData['es_estudiante_nuevo'] = true;
        }

        $this->merge($cleanData);
    }

    /**
     * Get the validated data from the request with defaults.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Asegurar que se genere código de estudiante si no se proporciona
        if (empty($validated['codigo_estudiante'])) {
            $validated['codigo_estudiante'] = \App\Models\Estudiante::generateNextCodigo();
        }

        return $validated;
    }
}