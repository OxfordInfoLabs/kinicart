<?php

namespace Kinicart\Objects\Pricing;

use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * Currency class - encodes the in use currencies for the system
 *
 * Class Currency
 *
 * @table kc_currency
 * @generate
 */
class Currency extends ActiveRecord {

    /**
     * The 3 character code for the currency
     *
     * @primaryKey
     * @var string
     */
    private $code;


    /**
     * Friendly description for this currency (e.g. US Dollars)
     *
     * @var string
     */
    private $description;


    /**
     * The exchange rate from the system base rate.
     *
     * @var float
     */
    private $exchangeRateFromBase;


    /**
     * Default currency
     *
     * @var boolean
     */
    private $defaultCurrency;


    /**
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code) {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getExchangeRateFromBase() {
        return $this->exchangeRateFromBase;
    }

    /**
     * @param float $exchangeRateFromBase
     */
    public function setExchangeRateFromBase($exchangeRateFromBase) {
        $this->exchangeRateFromBase = $exchangeRateFromBase;
    }

    /**
     * @return bool
     */
    public function isDefaultCurrency() {
        return $this->defaultCurrency;
    }

    /**
     * @param bool $defaultCurrency
     */
    public function setDefaultCurrency($defaultCurrency) {
        $this->defaultCurrency = $defaultCurrency;
    }


}
