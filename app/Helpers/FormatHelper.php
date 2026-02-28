<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format angka menjadi Rupiah
     * 
     * @param float|int $amount
     * @param bool $withRp
     * @return string
     */
    public static function rupiah($amount, $withRp = true)
    {
        $formatted = number_format($amount, 0, ',', '.');

        return $withRp ? "Rp {$formatted}" : $formatted;
    }

    /**
     * Format angka menjadi Rupiah tanpa prefix Rp
     */
    public static function rupiahNumber($amount)
    {
        return self::rupiah($amount, false);
    }

    /**
     * Format persentase
     */
    public static function percent($value, $decimals = 2)
    {
        return number_format($value, $decimals, ',', '.') . '%';
    }

    /**
     * Format angka standar
     */
    public static function number($value, $decimals = 0)
    {
        return number_format($value, $decimals, ',', '.');
    }
}
