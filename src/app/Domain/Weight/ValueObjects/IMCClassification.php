<?php

namespace App\Domain\Weight\ValueObjects;

/**
 * Value Object para Classificação de IMC
 *
 * Encapsula toda a lógica de classificação de IMC conforme OMS.
 * DDD: Value Object que representa uma classificação completa.
 */
class IMCClassification
{
    private float $imc;
    private ?int $age;
    private string $classification;
    private string $badge;
    private string $message;
    private string $observation;

    public function __construct(float $value, ?int $age = null)
    {
        $this->imc = $value;
        $this->age = $age;
        $this->classify();
    }

    private function classify(): void
    {
        $isElderly = $this->age && $this->age >= 65;

        if ($this->imc < 18.5) {
            $this->classification = 'Peso Insuficiente';
            $this->badge = 'blue';
            $this->message = 'Seu IMC está abaixo do peso considerado normal.';
            $this->observation = $isElderly
                ? 'Para idosos, isso pode indicar desnutrição. Recomenda-se acompanhamento médico.'
                : 'Consulte um nutricionista para orientação personalizada.';
        } elseif ($this->imc >= 18.5 && $this->imc < 25) {
            $this->classification = 'Peso Normal';
            $this->badge = 'green';
            $this->message = 'Parabéns! Seu IMC está na faixa considerada normal.';
            $this->observation = $isElderly
                ? 'Para idosos, o intervalo ideal é 22-27. Seu peso está bem posicionado!'
                : 'Mantenha hábitos saudáveis de alimentação e exercício físico.';
        } elseif ($this->imc >= 25 && $this->imc < 30) {
            $this->classification = 'Sobrepeso';
            $this->badge = 'yellow';
            $this->message = 'Você está na faixa de sobrepeso.';
            $this->observation = 'Pequenas mudanças nos hábitos podem ajudar a atingir um peso mais saudável.';
        } elseif ($this->imc >= 30 && $this->imc < 35) {
            $this->classification = 'Obesidade Grau I';
            $this->badge = 'orange';
            $this->message = 'Você está na faixa de obesidade grau I.';
            $this->observation = 'Recomenda-se trabalhar com um nutricionista para estruturar um plano adequado.';
        } elseif ($this->imc >= 35 && $this->imc < 40) {
            $this->classification = 'Obesidade Grau II';
            $this->badge = 'red';
            $this->message = 'Você está na faixa de obesidade grau II.';
            $this->observation = 'É importante procurar orientação profissional para avaliar sua saúde geral.';
        } else {
            $this->classification = 'Obesidade Grau III';
            $this->badge = 'red';
            $this->message = 'Você está na faixa de obesidade grau III.';
            $this->observation = 'Recomenda-se fortemente acompanhamento médico e nutricional.';
        }
    }

    public function classification(): string
    {
        return $this->classification;
    }

    public function badge(): string
    {
        return $this->badge;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function observation(): string
    {
        return $this->observation;
    }

    public function imc(): float
    {
        return $this->imc;
    }

    public function age(): ?int
    {
        return $this->age;
    }

    public function isElderly(): bool
    {
        return $this->age !== null && $this->age >= 65;
    }
}
