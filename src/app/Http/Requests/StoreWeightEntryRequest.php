<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para criação de entrada de peso
 *
 * SOLID: Single Responsibility - validação centralizada.
 * Separa validação do controller (Clean Code).
 */
class StoreWeightEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('patient');
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
