<?php

namespace App\Domain\Water\Services;

use App\Domain\Water\ValueObjects\WaterVolume;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Domain Service para Cálculos de Hidratação
 *
 * SOLID: Single Responsibility - apenas lógica de água.
 * DDD: Value Objects (WaterVolume) e regras de hidratação.
 */
class WaterHydrationService
{
    /**
     * Define meta de água diária conforme padrão OMS
     * Fórmula: peso (kg) × 35ml
     */
    public function calculateDailyGoal(?\App\Models\Patient $patient = null): WaterVolume
    {
        if ($patient && $patient->weight) {
            $goalMl = (int) round($patient->weight * 35);
            return WaterVolume::fromMilliliters($goalMl);
        }

        // Valor padrão recomendado pela OMS
        return WaterVolume::fromMilliliters(2500);
    }

    /**
     * Calcula percentual de meta atingida
     */
    public function calculateGoalProgress(WaterVolume $consumed, WaterVolume $goal): int
    {
        if ($goal->milliliters() === 0) {
            return 0;
        }

        $percentage = ($consumed->milliliters() / $goal->milliliters()) * 100;

        return min((int) $percentage, 100);
    }

    /**
     * Calcula consumo total em um período
     */
    public function calculatePeriodTotal(Collection $consumptions): WaterVolume
    {
        $totalMl = $consumptions->sum('amount_ml');

        return WaterVolume::fromMilliliters((int) $totalMl);
    }

    /**
     * Calcula média diária de consumo
     */
    public function calculateDailyAverage(Collection $consumptions, int $days): WaterVolume
    {
        if ($days === 0) {
            return WaterVolume::fromMilliliters(0);
        }

        $total = $this->calculatePeriodTotal($consumptions);
        $average = (int) round($total->milliliters() / $days);

        return WaterVolume::fromMilliliters($average);
    }

    /**
     * Valida se um volume de água está dentro dos limites
     */
    public function isValidVolume(int $milliliters): bool
    {
        try {
            WaterVolume::fromMilliliters($milliliters);
            return true;
        } catch (\InvalidArgumentException) {
            return false;
        }
    }

    /**
     * Normaliza volume para gráfico (0-100)
     */
    public function normalizeVolumeForChart(WaterVolume $volume, WaterVolume $min, WaterVolume $max): float
    {
        $minMl = $min->milliliters();
        $maxMl = $max->milliliters();

        if ($maxMl === $minMl) {
            return 50;
        }

        $volumeMl = $volume->milliliters();

        return (($volumeMl - $minMl) / ($maxMl - $minMl)) * 100;
    }

    /**
     * Retorna mensagem motivacional baseada no progresso
     */
    public function getMotivationalMessage(int $progressPercentage): string
    {
        if ($progressPercentage >= 100) {
            return '⭐ Meta atingida! Parabéns pela dedicação!';
        } elseif ($progressPercentage >= 75) {
            return '💪 Quase lá! Você está indo bem!';
        } elseif ($progressPercentage >= 50) {
            return '💧 Você está no caminho certo!';
        } elseif ($progressPercentage >= 25) {
            return '📍 Continue bebendo água!';
        }

        return '💦 Comece a beber água agora!';
    }

    /**
     * Obtém dados de consumo dos últimos N dias
     */
    public function getSevenDaysData(Collection $consumptions): array
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = now()->subDays($i)->locale('pt_BR')->dayName;
            $shortDay = now()->subDays($i)->format('d/m');

            $consumption = $consumptions
                ->where('consumption_date', $date)
                ->sum('amount_ml');

            $data[] = [
                'date' => $date,
                'day' => $dayName,
                'shortDay' => $shortDay,
                'amount_ml' => (int) $consumption,
                'amount_liters' => round($consumption / 1000, 2),
            ];
        }

        return $data;
    }
}
