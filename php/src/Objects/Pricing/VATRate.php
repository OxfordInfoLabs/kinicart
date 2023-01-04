<?php


namespace Kinicart\Objects\Pricing;

/**
 * @table kc_vat_rate
 * @generate
 */
class VATRate {

    /**
     * @var string
     * @primaryKey
     */
    private $countryCode;


    /**
     * @var float
     */
    private $vatPercentage;

    /**
     * VATRate constructor.
     *
     * @param string $countryCode
     * @param float $vatPercentage
     */
    public function __construct($countryCode, $vatPercentage) {
        $this->countryCode = $countryCode;
        $this->vatPercentage = $vatPercentage;
    }


    /**
     * @return string
     */
    public function getCountryCode() {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode) {
        $this->countryCode = $countryCode;
    }

    /**
     * @return float
     */
    public function getVatPercentage() {
        return $this->vatPercentage;
    }

    /**
     * @param float $vatPercentage
     */
    public function setVatPercentage($vatPercentage) {
        $this->vatPercentage = $vatPercentage;
    }


}