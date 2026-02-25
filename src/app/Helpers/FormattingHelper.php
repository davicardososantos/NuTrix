<?php

namespace App\Helpers;

class FormattingHelper
{
    /**
     * Formatar número decimal com vírgula como separador
     * @param float $number
     * @param int $decimals
     * @return string
     */
    public static function formatDecimal($number, $decimals = 2)
    {
        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Formatar litros com vírgula
     * @param float $liters
     * @return string
     */
    public static function formatLiters($liters)
    {
        return self::formatDecimal($liters, 1) . ' L';
    }

    /**
     * Formatar centímetros com vírgula
     * @param float $cm
     * @return string
     */
    public static function formatHeight($cm)
    {
        return self::formatDecimal($cm, 1) . ' cm';
    }

    /**
     * Formatar quilogramas com vírgula
     * @param float $kg
     * @return string
     */
    public static function formatWeight($kg)
    {
        return self::formatDecimal($kg, 1) . ' kg';
    }
}
