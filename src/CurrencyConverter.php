<?php

namespace Submtd\CurrencyConverter;

use Submtd\HttpRequest\HttpRequest;

class CurrencyConverter
{
    /**
     * Currency to convert from
     * @var string $from
     */
    protected $from;

    /**
     * Currency to convert to
     * @var string $to
     */
    protected $to;

    /**
     * Amount to convert
     * @var float $amount
     */
    protected $amount;

    /**
     * Class constructor
     * @param string $from
     * @param string $to
     * @param float $amount
     */
    public function __construct(string $from = null, string $to = null, float $amount = 1)
    {
        $this->from($from);
        $this->to($to);
        $this->amount($amount);
    }

    /**
     * Static constructor
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return Submtd\CurrencyConverter\CurrencyConverter
     */
    public static function init(string $from = null, string $to = null, float $amount = 1) : CurrencyConverter
    {
        return new static($from, $to, $amount);
    }

    /**
     * From getter
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * From setter
     * @param string $from
     * @return Submtd\CurrencyConverter\CurrencyConverter
     */
    public function from(string $from = null) : CurrencyConverter
    {
        $this->from = strtoupper($from);
        return $this;
    }

    /**
     * To getter
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * To setter
     * @param string $to
     * @return Submtd\CurrencyConverter\CurrencyConverter
     */
    public function to(string $to = null) : CurrencyConverter
    {
        $this->to = strtoupper($to);
        return $this;
    }

    /**
     * Amount getter
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Amount setter
     * @param float $amount
     * @return Submtd\CurrencyConverter\CurrencyConverter
     */
    public function amount(float $amount = null) : CurrencyConverter
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Convert
     * @return float
     */
    public function convert(string $from = null, string $to = null, float $amount = null)
    {
        $from = $from ?? $this->getFrom();
        $this->from($from);
        $to = $to ?? $this->getTo();
        $this->to($to);
        $amount = $amount ?? $this->getAmount();
        $this->amount($amount);
        $http = new HttpRequest();
        $http->url('https://min-api.cryptocompare.com/data/price?fsym=' . $from . '&tsyms=' . $to);
        $http->header('Accept', 'application/json');
        $response = $http->request()->getResponse();
        if (!isset(json_decode($response)->$to)) {
            if (isset(json_decode($response)->Type) && isset(json_decode($response)->Message)) {
                throw new \Exception(json_decode($response)->Message, json_decode($response)->Type);
            }
            throw new \Exception('Unknown error', 500);
        }
        // super hacky way to get the price and avoid floating point precision issues
        $price = substr($response, strpos($response, ':') + 1, -1);
        return bcmul($amount, $price, 16);
    }
}
