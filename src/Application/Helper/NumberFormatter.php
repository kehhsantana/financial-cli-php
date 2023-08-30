<?php

namespace App\Application\Helper;

class NumberFormatter
{
    public static function formatNumberToTwoDecimals($number)
    {
        return number_format($number, 2, '.', '');
    }
}