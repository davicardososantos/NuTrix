<?php

namespace App\Application\Dto\Weight;

/**
 * DTO (Data Transfer Object) para exibição de entrada de peso
 *
 * Design Pattern: DTO transfere dados entre camadas sem lógica.
 * SOLID: Responsabilidade única - apenas transportar dados.
 * Sem efeitos colaterais, apenas estrutura de dados.
 */
class WeightEntryDto
{
    public function __construct(
        public readonly int $id,
        public readonly float $weight_kg,
        public readonly string $measured_date,
        public readonly string $formatted_date,
        public readonly string $created_at,
        public readonly string $relative_time,
    ) {}

    public static function from(\App\Models\WeightEntry $entry): self
    {
        return new self(
            id: $entry->id,
            weight_kg: $entry->weight_kg,
            measured_date: $entry->measured_date->format('Y-m-d'),
            formatted_date: $entry->measured_date->format('d/m/Y'),
            created_at: $entry->created_at->format('Y-m-d H:i:s'),
            relative_time: $entry->created_at->locale('pt_BR')->diffForHumans(),
        );
    }

    public static function collection(\Illuminate\Support\Collection $entries): array
    {
        return $entries->map(fn($entry) => self::from($entry))->toArray();
    }
}
