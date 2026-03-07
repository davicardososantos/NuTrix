<?php

namespace App\Application\Dto\Weight;

use App\Domain\Weight\ValueObjects\IMCClassification;

/**
 * DTO para exibição de dados de IMC
 *
 * Encapsula resultado de classificação de IMC para apresentação.
 */
class IMCDto
{
    public function __construct(
        public readonly float $value,
        public readonly string $classification,
        public readonly string $badge,
        public readonly string $message,
        public readonly string $observation,
        public readonly ?int $age,
        public readonly bool $isElderly,
    ) {}

    public static function from(IMCClassification $classification): self
    {
        return new self(
            value: $classification->imc(),
            classification: $classification->classification(),
            badge: $classification->badge(),
            message: $classification->message(),
            observation: $classification->observation(),
            age: $classification->age(),
            isElderly: $classification->isElderly(),
        );
    }

    public function getBadgeClasses(): string
    {
        $badges = [
            'green' => 'bg-green-100 text-green-700 border border-green-300',
            'yellow' => 'bg-yellow-100 text-yellow-700 border border-yellow-300',
            'orange' => 'bg-orange-100 text-orange-700 border border-orange-300',
            'red' => 'bg-red-100 text-red-700 border border-red-300',
            'blue' => 'bg-blue-100 text-blue-700 border border-blue-300',
            'gray' => 'bg-gray-100 text-gray-700 border border-gray-300',
        ];

        return $badges[$this->badge] ?? $badges['gray'];
    }

    public function getIconClasses(): string
    {
        $colors = [
            'green' => 'text-green-600',
            'yellow' => 'text-yellow-600',
            'orange' => 'text-orange-600',
            'red' => 'text-red-600',
            'blue' => 'text-blue-600',
            'gray' => 'text-gray-600',
        ];

        return $colors[$this->badge] ?? $colors['gray'];
    }
}
