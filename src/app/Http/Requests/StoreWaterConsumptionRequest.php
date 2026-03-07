<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para armazenamento de consumo de água
 */
class StoreWaterConsumptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('patient');
    }

    public function rules(): array
    {
        return [
            'amount_ml' => [
                'required',
                'integer',
                'min:1',
                'max:20000',
            ],
            'consumption_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'amount_ml.required' => 'A quantidade é obrigatória.',
            'amount_ml.integer' => 'A quantidade deve ser um número inteiro.',
            'amount_ml.min' => 'A quantidade deve ser maior que 0ml.',
            'amount_ml.max' => 'A quantidade deve ser menor que 20000ml.',
            'consumption_date.required' => 'A data é obrigatória.',
            'consumption_date.date' => 'A data deve ser válida.',
            'consumption_date.before_or_equal' => 'A data não pode ser no futuro.',
        ];
    }
}
