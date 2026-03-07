<?php

namespace App\Application\Dto\Water;

/**
 * DTO para exibição de consumo de água
 *
 * Transporta dados de hidratação formatados para apresentação.
 */
class WaterConsumptionDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $amount_ml,
        public readonly float $amount_liters,
        public readonly string $consumption_date,
        public readonly string $formatted_date,
        public readonly string $created_at,
        public readonly string $relative_time,
    ) {}

    public static function from(\App\Models\WaterConsumption $consumption): self
    {
        return new self(
            id: $consumption->id,
            amount_ml: $consumption->amount_ml,
            amount_liters: round($consumption->amount_ml / 1000, 2),
            consumption_date: $consumption->consumption_date->format('Y-m-d'),
            formatted_date: $consumption->consumption_date->format('d/m/Y'),
            created_at: $consumption->created_at->format('Y-m-d H:i:s'),
            relative_time: $consumption->created_at->locale('pt_BR')->diffForHumans(),
        );
    }

    public static function collection(\Illuminate\Support\Collection $consumptions): array
    {
        return $consumptions->map(fn($c) => self::from($c))->toArray();
    }
}
