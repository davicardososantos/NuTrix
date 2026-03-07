<?php

namespace App\Domain\Weight\ValueObjects;

/**
 * Value Object para representar peso em kg
 *
 * Encapsula o conceito de peso com validação e comportamentos específicos.
 * Exemplo de DDD (Domain-Driven Design): Value Objects imutáveis.
 * Garante que peso é sempre válido dentro do domínio.
 */
class WeightValue
{
    private readonly float $kilograms;

    public function __construct(float $kilograms)
    {
        if ($kilograms < 1 || $kilograms > 500) {
            throw new \InvalidArgumentException('Peso deve estar entre 1kg e 500kg');
        }

        $this->kilograms = round($kilograms, 2);
    }

    public static function fromKilograms(float $kg): self
    {
        return new self($kg);
    }

    public static function fromPounds(float $lbs): self
    {
        return new self($lbs / 2.20462);
    }

    public function kilograms(): float
    {
        return $this->kilograms;
    }

    public function pounds(): float
    {
        return round($this->kilograms * 2.20462, 2);
    }

    public function equals(WeightValue $other): bool
    {
        return $this->kilograms === $other->kilograms;
    }

    public function isGreaterThan(WeightValue $other): bool
    {
        return $this->kilograms > $other->kilograms;
    }

    public function isLessThan(WeightValue $other): bool
    {
        return $this->kilograms < $other->kilograms;
    }

    public function __toString(): string
    {
        return (string) $this->kilograms;
    }
}
