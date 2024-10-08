<?php
namespace App\Helpers;

use NumberFormatter;

class currency 
{
    public static function format($amount, $currency = null){
        $formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currency);
    }
}