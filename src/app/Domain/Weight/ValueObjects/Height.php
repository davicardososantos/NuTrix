<?php

namespace App\Domain\Weight\ValueObjects;

/**
 * Value Object para Altura em cm
 *
 * Encapsula medida de altura com conversões e validações.
 * DDD: Imutável e auto-validado.
 */
class Height
{
    private readonly float $centimeters;

    public function __construct(float $centimeters)
    {
        if ($centimeters < 50 || $centimeters > 250) {
            throw new \InvalidArgumentException('Altura deve estar entre 50cm e 250cm');
        }

        $this->centimeters = round($centimeters, 1);
    }

    public static function fromCentimeters(float $cm): self
    {
        return new self($cm);
    }

    public static function fromMeters(float $m): self
    {
        return new self($m * 100);
    }

    public static function fromInches(float $inches): self
    {
        return new self($inches * 2.54);
    }

    public function centimeters(): float
    {
        return $this->centimeters;
    }

    public function meters(): float
    {
        return round($this->centimeters / 100, 2);
    }

    public function inches(): float
    {
        return round($this->centimeters / 2.54, 2);
    }

    public function __toString(): string
    {
        return (string) $this->centimeters;
    }
}
