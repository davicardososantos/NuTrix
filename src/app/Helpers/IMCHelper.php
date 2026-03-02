<?php

namespace App\Helpers;

use Carbon\Carbon;

class IMCHelper
{
    /**
     * Calcula o IMC baseado em altura (cm) e peso (kg)
     * 
     * @param float $weight Peso em kg
     * @param float $height Altura em cm
     * @return float|null IMC calculado ou null se dados inválidos
     */
    public static function calculate($weight, $height)
    {
        if (!$weight || !$height || $height == 0) {
            return null;
        }

        // Converter altura de cm para metros
        $heightInMeters = $height / 100;
        
        return round($weight / ($heightInMeters ** 2), 1);
    }

    /**
     * Retorna a classificação de IMC com base no valor e idade
     * 
     * @param float $imc Valor do IMC
     * @param string $birthDate Data de nascimento (Y-m-d)
     * @return array Com 'classificacao', 'badge', 'cor' e 'observacoes'
     */
    public static function classify($imc, $birthDate = null)
    {
        if ($imc === null) {
            return [
                'classificacao' => 'Dados Insuficientes',
                'badge' => 'gray',
                'cor' => 'text-gray-700',
                'observacoes' => 'Cadastre peso e altura para calcular o IMC.',
            ];
        }

        $isIdoso = false;
        $isAdultoJovem = false;

        if ($birthDate) {
            $age = Carbon::parse($birthDate)->age;
            $isIdoso = $age >= 65;
            $isAdultoJovem = $age >= 18 && $age < 60;
        }

        // Classificações padrão (OMS)
        if ($imc < 18.5) {
            return [
                'classificacao' => 'Peso Insuficiente',
                'badge' => 'blue',
                'cor' => 'text-blue-700',
                'observacoes' => 'Seu IMC está abaixo do peso considerado normal. Consulte um nutricionista para orientação personalizada sobre alimentação saudável.',
                'observacoesIdoso' => 'Para idosos, esse resultado pode indicar desnutrição. Recomenda-se acompanhamento médico mais próximo.',
            ];
        } elseif ($imc >= 18.5 && $imc < 25) {
            return [
                'classificacao' => 'Peso Normal',
                'badge' => 'green',
                'cor' => 'text-green-700',
                'observacoes' => 'Parabéns! Seu IMC está na faixa considerada normal. Mantenha hábitos saudáveis de alimentação e exercício físico.',
                'observacoesIdoso' => 'Para idosos, o intervalo ideal é ligeiramente diferente (IMC 22-27). Seu peso está bem posicionado!',
            ];
        } elseif ($imc >= 25 && $imc < 30) {
            return [
                'classificacao' => 'Sobrepeso',
                'badge' => 'yellow',
                'cor' => 'text-yellow-700',
                'observacoes' => 'Você está na faixa de sobrepeso. Pequenas mudanças nos hábitos de alimentação e aumento de atividade física podem ajudar a atingir um peso mais saudável.',
                'observacoesAtleta' => 'Se você é atleta ou pratica atividades físicas intensas, tenha em mente que o IMC pode não refletir corretamente sua composição corporal devido à massa muscular.',
            ];
        } elseif ($imc >= 30 && $imc < 35) {
            return [
                'classificacao' => 'Obesidade Grau I',
                'badge' => 'orange',
                'cor' => 'text-orange-700',
                'observacoes' => 'Você está na faixa de obesidade grau I. Recomenda-se trabalhar com um nutricionista para estruturar um plano alimentar adequado e aumentar a atividade física gradualmente.',
            ];
        } elseif ($imc >= 35 && $imc < 40) {
            return [
                'classificacao' => 'Obesidade Grau II',
                'badge' => 'red',
                'cor' => 'text-red-700',
                'observacoes' => 'Você está na faixa de obesidade grau II. É importante procurar orientação profissional de um nutricionista e médico para avaliar sua saúde geral e estruturar um plano de intervenção.',
            ];
        } else {
            return [
                'classificacao' => 'Obesidade Grau III',
                'badge' => 'red',
                'cor' => 'text-red-700',
                'observacoes' => 'Você está na faixa de obesidade grau III. Recomenda-se fortemente acompanhamento médico e nutricional para avaliar riscos à saúde e estruturar um plano de tratamento individualizado.',
            ];
        }
    }

    /**
     * Retorna cor de badge baseada no tipo
     * 
     * @param string $badge 'green', 'yellow', 'orange', 'red', 'blue', 'gray'
     * @return string Classes Tailwind CSS
     */
    public static function getBadgeClasses($badge)
    {
        $badges = [
            'green' => 'bg-green-100 text-green-700 border border-green-300',
            'yellow' => 'bg-yellow-100 text-yellow-700 border border-yellow-300',
            'orange' => 'bg-orange-100 text-orange-700 border border-orange-300',
            'red' => 'bg-red-100 text-red-700 border border-red-300',
            'blue' => 'bg-blue-100 text-blue-700 border border-blue-300',
            'gray' => 'bg-gray-100 text-gray-700 border border-gray-300',
        ];

        return $badges[$badge] ?? $badges['gray'];
    }

    /**
     * Retorna cor de ícone baseada no tipo
     * 
     * @param string $badge 'green', 'yellow', 'orange', 'red', 'blue', 'gray'
     * @return string Classes Tailwind CSS
     */
    public static function getIconClasses($badge)
    {
        $colors = [
            'green' => 'text-green-600',
            'yellow' => 'text-yellow-600',
            'orange' => 'text-orange-600',
            'red' => 'text-red-600',
            'blue' => 'text-blue-600',
            'gray' => 'text-gray-600',
        ];

        return $colors[$badge] ?? $colors['gray'];
    }

    /**
     * Retorna a observação apropriada baseada no perfil
     * 
     * @param array $classification Resultado de classify()
     * @param string $birthDate Data de nascimento
     * @param bool $isAthlete Se é atleta
     * @return string Observação apropriada
     */
    public static function getObservacao($classification, $birthDate = null, $isAthlete = false)
    {
        $age = $birthDate ? Carbon::parse($birthDate)->age : null;
        
        if ($isAthlete && isset($classification['observacoesAtleta'])) {
            return $classification['observacoesAtleta'];
        }
        
        if ($age && $age >= 65 && isset($classification['observacoesIdoso'])) {
            return $classification['observacoesIdoso'];
        }

        return $classification['observacoes'] ?? '';
    }
}
