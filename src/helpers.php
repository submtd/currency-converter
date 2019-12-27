<?php

if (!function_exists('currency_converter')) {
    function currency_converter(string $from = null, string $to = null, float $amount = 1) : Submtd\CurrencyConverter\CurrencyConverter
    {
        return new Submtd\CurrencyConverter\CurrencyConverter($from, $to, $amount);
    }
}
