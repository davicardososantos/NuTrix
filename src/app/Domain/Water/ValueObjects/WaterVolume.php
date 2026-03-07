<?php

namespace App\Domain\Water\ValueObjects;

/**
 * Value Object para Volume de Água
 *
 * Encapsula quantidade de água com conversão entre mililitros e litros.
 * DDD: Imutável com conversões automáticas.
 */
class WaterVolume
{
    private readonly int $milliliters;

    public function __construct(int $milliliters)
    {
        if ($milliliters < 0 || $milliliters > 20000) {
            throw new \InvalidArgumentException('Volume deve estar entre 0ml e 20000ml');
        }

        $this->milliliters = $milliliters;
    }

    public static function fromMilliliters(int $ml): self
    {
        return new self($ml);
    }

    public static function fromLiters(float $liters): self
    {
        return new self((int) ($liters * 1000));
    }

    public function milliliters(): int
    {
        return $this->milliliters;
    }

    public function liters(): float
    {
        return round($this->milliliters / 1000, 2);
    }

    public function cups(): float
    {
        return round($this->milliliters / 240, 2);
    }

    public function isZero(): bool
    {
        return $this->milliliters === 0;
    }

    public function equals(WaterVolume $other): bool
    {
        return $this->milliliters === $other->milliliters;
    }

    public function isGreaterThan(WaterVolume $other): bool
    {
        return $this->milliliters > $other->milliliters;
    }

    public function __toString(): string
    {
        return $this->milliliters . 'ml';
    }
}
