<?php

namespace App\Domain\Monitoring\Services;

use App\Domain\Weight\Services\WeightCalculationService;
use App\Domain\Water\Services\WaterHydrationService;
use App\Domain\Weight\ValueObjects\WeightValue;
use App\Domain\Weight\ValueObjects\Height;
use App\Domain\Water\ValueObjects\WaterVolume;
use App\Models\Patient;
use Illuminate\Support\Collection;

/**
 * Domain Service para Monitoramento Integrado
 *
 * SOLID: Single Responsibility - orquestra serviços de peso e água.
 * Design Pattern: Facade que coordena múltiplos serviços de domínio.
 */
class MonitoringService
{
    public function __construct(
        private WeightCalculationService $weightService,
        private WaterHydrationService $hydrationService,
    ) {}

    /**
     * Coleta e normaliza dados para dashboard de monitoramento
     */
    public function compileDashboardData(Patient $patient): array
    {
        $user = $patient->user;

        if (!$user) {
            return $this->getEmptyDashboard();
        }

        // Dados de peso
        $weightChartEntries = $user->weightEntries()
            ->orderByDesc('measured_date')
            ->limit(10)
            ->get()
            ->sortBy('measured_date')
            ->values();

        $latestWeight = $user->weightEntries()->orderByDesc('measured_date')->first();

        // Dados de água
        $waterChartEntries = $user->waterConsumptions()
            ->where('consumption_date', '>=', now()->subDays(30))
            ->orderBy('consumption_date')
            ->get();

        $latestWater = $user->waterConsumptions()->orderByDesc('consumption_date')->first();

        // Calcula IMC
        $imc = null;
        $imcClassification = null;
        if ($latestWeight && $patient->height) {
            $weight = WeightValue::fromKilograms($latestWeight->weight_kg);
            $height = Height::fromCentimeters($patient->height);
            $imc = $this->weightService->calculateIMC($weight, $height, $patient->birth_date);
            $imcClassification = $imc->classify($patient->birth_date);
        }

        // Calcula meta de água diária
        $waterGoal = $this->hydrationService->calculateDailyGoal($patient);
        $waterProgress = 0;
        if ($latestWater) {
            $waterProgress = $this->hydrationService->calculateGoalProgress(
                WaterVolume::fromMilliliters($latestWater->amount_ml),
                $waterGoal
            );
        }

        return [
            'patient' => $patient,
            'weightChartEntries' => $weightChartEntries,
            'latestWeight' => $latestWeight,
            'waterChartEntries' => $waterChartEntries,
            'latestWater' => $latestWater,
            'imc' => $imc,
            'imcClassification' => $imcClassification,
            'waterGoal' => $waterGoal,
            'waterProgress' => $waterProgress,
        ];
    }

    /**
     * Retorna estrutura vazia quando não há dados
     */
    private function getEmptyDashboard(): array
    {
        return [
            'patient' => null,
            'weightChartEntries' => collect([]),
            'latestWeight' => null,
            'waterChartEntries' => collect([]),
            'latestWater' => null,
            'imc' => null,
            'imcClassification' => null,
            'waterGoal' => $this->hydrationService->calculateDailyGoal(),
            'waterProgress' => 0,
        ];
    }

    /**
     * Gera insights de monitoramento
     */
    public function generateInsights(Patient $patient): array
    {
        $user = $patient->user;
        $insights = [];

        if (!$user) {
            return $insights;
        }

        // Insight de peso
        $latestWeight = $user->weightEntries()->orderByDesc('measured_date')->first();
        $previousWeight = $user->weightEntries()
            ->where('measured_date', '<', $latestWeight?->measured_date)
            ->orderByDesc('measured_date')
            ->first();

        if ($latestWeight && $previousWeight) {
            $weightChange = $previousWeight->weight_kg - $latestWeight->weight_kg;
            if ($weightChange > 0) {
                $insights['weight'] = "Perda de {$weightChange}kg desde última medição";
            } elseif ($weightChange < 0) {
                $insights['weight'] = "Ganho de " . abs($weightChange) . "kg desde última medição";
            }
        }

        // Insight de água
        $latestWater = $user->waterConsumptions()->orderByDesc('consumption_date')->first();
        if ($latestWater) {
            $waterGoal = $this->hydrationService->calculateDailyGoal($patient);
            $progress = $this->hydrationService->calculateGoalProgress(
                WaterVolume::fromMilliliters($latestWater->amount_ml),
                $waterGoal
            );
            $insights['water'] = $this->hydrationService->getMotivationalMessage($progress);
        }

        return $insights;
    }
}
