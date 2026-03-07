<?php

namespace App\Application\ViewModels;

use Illuminate\Support\Collection;

/**
 * ViewModel para Dados de Gráfico SVG
 *
 * Design Pattern: ViewModel - prepara dados estruturados para renderização de gráficos.
 * Encapsula lógica de normalização e formatação de pontos de gráfico.
 */
class ChartPointsViewModel
{
    private array $points = [];
    private float $minValue;
    private float $maxValue;
    private float $displayRange;

    public function __construct(
        private Collection $entries,
        private string $valueKey,
        private string $dateKey,
        private string $dateFormat = 'd/m',
    ) {
        $this->calculateRange();
        $this->buildPoints();
    }

    private function calculateRange(): void
    {
        if ($this->entries->isEmpty()) {
            $this->minValue = 0;
            $this->maxValue = 100;
            $this->displayRange = 100;
            return;
        }

        $values = $this->entries->pluck($this->valueKey)->map(fn($v) => (float) $v);

        $min = $values->min();
        $max = $values->max();
        $padding = ($max - $min) * 0.15;

        $this->minValue = max(0, floor(($min - $padding) * 2) / 2);
        $this->maxValue = ceil(($max + $padding) * 2) / 2;
        $this->displayRange = max($this->maxValue - $this->minValue, 1);
    }

    private function buildPoints(): void
    {
        $count = count($this->entries);

        foreach ($this->entries as $index => $entry) {
            $value = (float) $entry->{$this->valueKey};
            $date = $entry->{$this->dateKey};

            // Normaliza X (0-100) baseado em posição
            $x = $count > 1 ? ($index / ($count - 1)) * 100 : 50;

            // Normaliza Y (0-100) baseado no valor
            $y = 100 - ((($value - $this->minValue) / $this->displayRange) * 100);

            $this->points[] = [
                'index' => $index,
                'value' => $value,
                'x' => number_format($x, 2, '.', ''),
                'y' => number_format($y, 2, '.', ''),
                'date' => is_object($date) ? $date->format($this->dateFormat) : $date,
                'fullDate' => is_object($date) ? $date : $date,
            ];
        }
    }

    public function points(): array
    {
        return $this->points;
    }

    public function minValue(): float
    {
        return $this->minValue;
    }

    public function maxValue(): float
    {
        return $this->maxValue;
    }

    public function displayRange(): float
    {
        return $this->displayRange;
    }

    public function count(): int
    {
        return count($this->points);
    }

    public function isEmpty(): bool
    {
        return empty($this->points);
    }

    public function svgPolylinePoints(): string
    {
        return implode(' ', array_map(
            fn($p) => "50 + ({$p['x']} / 100 * 1130),({$p['y']} / 100 * 400)",
            $this->points
        ));
    }
}
