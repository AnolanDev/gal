<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Academic\Student::class);
    }

    public function rules(): array
    {
        return [
            'identification_number' => [
                'required',
                'string',
                'max:20',
                'unique:students,identification_number',
                'regex:/^[0-9]+$/', // Only numbers
            ],
            'identification_type' => [
                'required',
                Rule::in(['TI', 'RC', 'CC', 'CE', 'PP']),
            ],
            'first_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', // Only letters and accents
            ],
            'last_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            ],
            'birth_date' => [
                'required',
                'date',
                'before:today',
                'after:' . now()->subYears(18)->format('Y-m-d'), // Max 18 years old
            ],
            'gender' => [
                'required',
                Rule::in(['M', 'F', 'Other']),
            ],
            'address' => [
                'required',
                'string',
                'max:500',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:15',
                'regex:/^[\d\s\-\+\(\)]+$/', // Phone format
            ],
            'emergency_contact' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            ],
            'emergency_phone' => [
                'required',
                'string',
                'max:15',
                'regex:/^[\d\s\-\+\(\)]+$/',
            ],
            'blood_type' => [
                'nullable',
                Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            ],
            'medical_conditions' => [
                'nullable',
                'array',
                'max:10', // Max 10 conditions
            ],
            'medical_conditions.*' => [
                'string',
                'max:200',
            ],
            'grade_id' => [
                'required',
                'exists:grades,id',
                function ($attribute, $value, $fail) {
                    $grade = \App\Models\Academic\Grade::find($value);
                    if ($grade && !$grade->canEnrollStudent()) {
                        $fail('El grado seleccionado no tiene cupos disponibles.');
                    }
                },
            ],
            'parent_user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = \App\Models\User::find($value);
                    if ($user && !$user->hasRole('parent')) {
                        $fail('El usuario seleccionado no tiene rol de padre.');
                    }
                },
            ],
            'enrollment_date' => [
                'required',
                'date',
                'before_or_equal:today',
                'after:' . now()->subYear()->format('Y-m-d'), // Within last year
            ],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048', // 2MB max
                'dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'identification_number.required' => 'El número de identificación es obligatorio.',
            'identification_number.unique' => 'Ya existe un estudiante con este número de identificación.',
            'identification_number.regex' => 'El número de identificación solo debe contener números.',
            
            'first_name.required' => 'El primer nombre es obligatorio.',
            'first_name.regex' => 'El primer nombre solo debe contener letras.',
            
            'last_name.required' => 'El apellido es obligatorio.',
            'last_name.regex' => 'El apellido solo debe contener letras.',
            
            'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
            'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'birth_date.after' => 'El estudiante no puede tener más de 18 años.',
            
            'grade_id.required' => 'Debe seleccionar un grado.',
            'grade_id.exists' => 'El grado seleccionado no existe.',
            
            'parent_user_id.required' => 'Debe seleccionar un padre o acudiente.',
            'parent_user_id.exists' => 'El padre seleccionado no existe.',
            
            'emergency_contact.required' => 'El contacto de emergencia es obligatorio.',
            'emergency_contact.regex' => 'El contacto de emergencia solo debe contener letras.',
            
            'emergency_phone.required' => 'El teléfono de emergencia es obligatorio.',
            'emergency_phone.regex' => 'El formato del teléfono de emergencia no es válido.',
            
            'photo.image' => 'El archivo debe ser una imagen.',
            'photo.mimes' => 'La imagen debe ser en formato JPEG, PNG o JPG.',
            'photo.max' => 'La imagen no debe ser mayor a 2MB.',
            'photo.dimensions' => 'La imagen debe tener entre 100x100 y 1000x1000 píxeles.',
        ];
    }

    public function attributes(): array
    {
        return [
            'identification_number' => 'número de identificación',
            'identification_type' => 'tipo de identificación',
            'first_name' => 'primer nombre',
            'last_name' => 'apellido',
            'birth_date' => 'fecha de nacimiento',
            'gender' => 'género',
            'address' => 'dirección',
            'phone' => 'teléfono',
            'emergency_contact' => 'contacto de emergencia',
            'emergency_phone' => 'teléfono de emergencia',
            'blood_type' => 'tipo de sangre',
            'medical_conditions' => 'condiciones médicas',
            'grade_id' => 'grado',
            'parent_user_id' => 'padre o acudiente',
            'enrollment_date' => 'fecha de matrícula',
            'photo' => 'foto',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Clean and normalize data before validation
        $this->merge([
            'identification_number' => preg_replace('/[^0-9]/', '', $this->identification_number),
            'first_name' => ucwords(strtolower(trim($this->first_name))),
            'last_name' => ucwords(strtolower(trim($this->last_name))),
            'emergency_contact' => ucwords(strtolower(trim($this->emergency_contact))),
        ]);
    }
}