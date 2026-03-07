<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para atualização de paciente
 */
class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        $patient = $this->route('patient');

        return auth()->check() &&
               auth()->user()->hasRole('nutritionist') &&
               $patient->nutritionist_id === auth()->user()->nutritionist?->id;
    }

    public function rules(): array
    {
        $patientId = $this->route('patient')->id;

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:patients,email,{$patientId}"],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'biological_sex' => ['nullable', 'string', 'in:M,F,O'],
            'phone' => ['nullable', 'string', 'max:20'],
            'profession' => ['nullable', 'string', 'max:255'],
            'work_routine' => ['nullable', 'string'],
            'main_goal' => ['nullable', 'string'],
            'referral_source' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'numeric', 'min:1', 'max:500'],
            'height' => ['nullable', 'numeric', 'min:50', 'max:250'],
            'body_fat_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'calorie_target' => ['nullable', 'integer', 'min:0'],
            'clinical_history' => ['nullable', 'string'],
            'medical_notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'O nome do paciente é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.unique' => 'Este email já está registrado para outro paciente.',
            'birth_date.before' => 'A data de nascimento deve ser no passado.',
            'weight.numeric' => 'O peso deve ser um número válido.',
            'height.numeric' => 'A altura deve ser um número válido.',
        ];
    }
}
