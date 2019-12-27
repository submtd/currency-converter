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
    public function from(string $from) : CurrencyConverter
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
    public function to(string $to) : CurrencyConverter
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
    public function amount(float $amount) : CurrencyConverter
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
        if ($from) {
            $this->from($from);
        }
        if ($to) {
            $this->to($to);
        }
        if ($amount) {
            $this->amount($amount);
        }
        $http = new HttpRequest();
        $http->url('https://min-api.cryptocompare.com/data/price?fsym=' . $this->getFrom() . '&tsyms=' . $this->getTo());
        $http->header('Accept', 'application/json');
        return $http->request()->getResponse();
    }
}
