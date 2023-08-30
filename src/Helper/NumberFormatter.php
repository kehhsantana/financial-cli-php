<?php

namespace App\Helper;

class NumberFormatter
{
    public static function formatNumberToTwoDecimals($number)
    {
        return number_format($number, 2, '.', '');
    }
}