<?php

namespace App\Domain\Weight\ValueObjects;

use Carbon\Carbon;

/**
 * Value Object para IMC (Índice de Massa Corporal)
 *
 * Encapsula cálculo e classificação do IMC seguindo padrões OMS.
 * DDD: Regra de negócio crítica encapsulada em Value Object.
 * SOLID: Single Responsibility - apenas IMC, sem efeitos colaterais.
 */
class IMCValue
{
    private readonly float $value;

    public function __construct(float $imc)
    {
        $this->value = round($imc, 1);
    }

    public static function calculate(WeightValue $weight, Height $height): self
    {
        $heightInMeters = $height->meters();
        $imc = $weight->kilograms() / ($heightInMeters ** 2);

        return new self($imc);
    }

    public function value(): float
    {
        return $this->value;
    }

    /**
     * Classifica IMC de acordo com padrão OMS
     * Considera idade para classificação especial de idosos
     */
    public function classify(?Carbon $birthDate = null): IMCClassification
    {
        $age = $birthDate ? $birthDate->age : null;

        return new IMCClassification(
            value: $this->value,
            age: $age
        );
    }

    public function isUnderweight(): bool
    {
        return $this->value < 18.5;
    }

    public function isNormalWeight(): bool
    {
        return $this->value >= 18.5 && $this->value < 25;
    }

    public function isOverweight(): bool
    {
        return $this->value >= 25 && $this->value < 30;
    }

    public function isObese(): bool
    {
        return $this->value >= 30;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
