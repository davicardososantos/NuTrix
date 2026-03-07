<?php

namespace App\Application\ViewModels;

use App\Application\Dto\Weight\IMCDto;
use App\Application\Dto\Weight\WeightEntryDto;
use App\Application\Dto\Water\WaterConsumptionDto;
use Illuminate\Support\Collection;

/**
 * ViewModel para Dashboard de Monitoramento
 *
 * Design Pattern: ViewModel - agregador de dados para exibição.
 * Prepara todas as informações necessárias para renderização do dashboard.
 */
class MonitoringDashboardViewModel
{
    public function __construct(
        public readonly \App\Models\Patient $patient,
        public readonly ?WeightEntryDto $latestWeight,
        public readonly ?IMCDto $imc,
        public readonly ?WaterConsumptionDto $latestWater,
        public readonly ChartPointsViewModel $weightChart,
        public readonly ChartPointsViewModel $waterChart,
        public readonly array $waterGoal, // ['ml' => int, 'liters' => float]
        public readonly int $waterProgress,
    ) {}

    public function hasWeightData(): bool
    {
        return $this->latestWeight !== null && $this->weightChart->count() > 1;
    }

    public function hasWaterData(): bool
    {
        return $this->waterChart->count() > 1;
    }

    public function hasIMC(): bool
    {
        return $this->imc !== null;
    }

    public function weightChangePercentage(): ?float
    {
        if (!$this->latestWeight) {
            return null;
        }

        // Aqui você implementaria lógica de cálculo de percentual se necessário
        return null;
    }

    public function waterProgressLabel(): string
    {
        if ($this->waterProgress >= 100) {
            return '⭐ Meta atingida!';
        } elseif ($this->waterProgress >= 75) {
            return '💪 Quase lá!';
        } elseif ($this->waterProgress >= 50) {
            return '💧 No caminho certo';
        }

        return '💦 Continue!';
    }
}
