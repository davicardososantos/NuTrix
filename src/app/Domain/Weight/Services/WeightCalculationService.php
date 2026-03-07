<?php

namespace App\Domain\Weight\Services;

use App\Domain\Weight\ValueObjects\WeightValue;
use App\Domain\Weight\ValueObjects\Height;
use App\Domain\Weight\ValueObjects\IMCValue;
use App\Models\WeightEntry;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Domain Service para Cálculos de Peso
 *
 * Exemplo de SOLID: Single Responsibility - apenas lógica de peso.
 * DDD: Orquestra Value Objects e regras de negócio de peso.
 * Design Pattern: Service Layer encapsula domínio.
 */
class WeightCalculationService
{
    /**
     * Calcula alteração de peso entre duas medições
     */
    public function calculateWeightChange(WeightEntry $current, WeightEntry $previous): WeightValue
    {
        $currentWeight = WeightValue::fromKilograms($current->weight_kg);
        $previousWeight = WeightValue::fromKilograms($previous->weight_kg);

        // Retorna a diferença em kg
        $difference = $previousWeight->kilograms() - $currentWeight->kilograms();

        return WeightValue::fromKilograms(abs($difference));
    }

    /**
     * Calcula velocidade de ganho/perda de peso por dia
     */
    public function calculateWeightVelocity(
        WeightEntry $current,
        WeightEntry $previous
    ): float {
        $daysDifference = $previous->measured_date->diffInDays($current->measured_date);

        if ($daysDifference === 0) {
            return 0;
        }

        $currentWeight = WeightValue::fromKilograms($current->weight_kg);
        $previousWeight = WeightValue::fromKilograms($previous->weight_kg);

        $weightDifference = $previousWeight->kilograms() - $currentWeight->kilograms();

        return round($weightDifference / $daysDifference, 2);
    }

    /**
     * Calcula IMC a partir de peso e altura
     */
    public function calculateIMC(WeightValue $weight, Height $height, ?Carbon $birthDate = null): IMCValue
    {
        return IMCValue::calculate($weight, $height);
    }

    /**
     * Calcula estatísticas de peso para um período
     */
    public function calculateWeightStats(Collection $entries): array
    {
        if ($entries->isEmpty()) {
            return [
                'minimum' => null,
                'maximum' => null,
                'current' => null,
                'latest' => null,
                'oldest' => null,
                'difference' => null,
                'average' => null,
            ];
        }

        $weights = $entries->map(fn($e) => $e->weight_kg);

        $latest = $entries->first();
        $oldest = $entries->last();

        return [
            'minimum' => $weights->min(),
            'maximum' => $weights->max(),
            'current' => $latest->weight_kg,
            'latest' => $latest,
            'oldest' => $oldest,
            'difference' => $oldest->weight_kg - $latest->weight_kg,
            'average' => round($weights->avg(), 2),
        ];
    }

    /**
     * Valida se um peso está dentro dos limites aceitáveis
     */
    public function isValidWeight(float $weight): bool
    {
        try {
            WeightValue::fromKilograms($weight);
            return true;
        } catch (\InvalidArgumentException) {
            return false;
        }
    }

    /**
     * Normaliza peso para gráfico (0-100)
     */
    public function normalizeWeightForChart(float $weight, float $minWeight, float $maxWeight): float
    {
        if ($maxWeight === $minWeight) {
            return 50;
        }

        return (($weight - $minWeight) / ($maxWeight - $minWeight)) * 100;
    }
}
