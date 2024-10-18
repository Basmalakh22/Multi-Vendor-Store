<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

use NumberFormatter;

class currency
{
    public function __invoke(...$params){
        return static::format(...$params);
    }
    public static function format($amount, $currency = null){
        $baseCurrency = config('app.currency' ,'SAR');
        $formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);
        if($currency === null){
            $currency = Session::get('currency_code',$baseCurrency);
        }
        if($currency != $baseCurrency){
            $rate =  Cache::get('currency_rate_' . $currency,1);
            $amount = $amount * $rate;
        }
        return $formatter->formatCurrency($amount, $currency);
    }
}
