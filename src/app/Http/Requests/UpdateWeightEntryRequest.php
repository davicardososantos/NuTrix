<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para atualização de entrada de peso
 */
class UpdateWeightEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() &&
               auth()->user()->hasRole('patient') &&
               $this->route('weightEntry')?->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'weight_kg' => [
                'required',
                'numeric',
                'min:1',
                'max:500',
            ],
            'measured_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'weight_kg.required' => 'O peso é obrigatório.',
            'weight_kg.numeric' => 'O peso deve ser um número válido.',
            'weight_kg.min' => 'O peso deve ser maior que 1kg.',
            'weight_kg.max' => 'O peso deve ser menor que 500kg.',
            'measured_date.required' => 'A data é obrigatória.',
            'measured_date.date' => 'A data deve ser válida.',
            'measured_date.before_or_equal' => 'A data não pode ser no futuro.',
        ];
    }
}
