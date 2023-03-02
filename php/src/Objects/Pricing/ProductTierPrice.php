<?php


namespace Kinicart\Objects\Pricing;

/**
 * Tier price for a product.
 *
 * @table kc_product_tier_price
 * @generate
 *
 */
class ProductTierPrice {


    /**
     * The parent base price for which this tier price is defined.
     *
     * @var integer
     * @primaryKey
     */
    private $basePriceId;


    /**
     * The tier for which this price is defined.
     *
     * @var integer
     * @primaryKey
     */
    private $tierId;


    /**
     * The currency code for which this price is being defined
     *
     * @var string
     * @primaryKey
     * @maxLength 10
     */
    private $currencyCode;


    /**
     * The price for this product for this tier
     *
     * @var float
     * @required
     */
    private $tierPrice;

    /**
     * @var string
     */
    private $pricingRule;

    /**
     * @var float
     */
    private $value;


    /**
     * ProductTierPrice constructor.
     *
     * @param int $tierId
     * @param string $pricingRule
     * @param float $value
     * @param string $currencyCode
     */
    public function __construct($tierId = null, $pricingRule = null, $value = null, $currencyCode = null) {
        $this->tierId = $tierId;
        $this->pricingRule = $pricingRule;
        $this->value = $value;
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return int
     */
    public function getBasePriceId() {
        return $this->basePriceId;
    }

    /**
     * @param int $basePriceId
     */
    public function setBasePriceId($basePriceId) {
        $this->basePriceId = $basePriceId;
    }

    /**
     * @return int
     */
    public function getTierId() {
        return $this->tierId;
    }

    /**
     * @param int $tierId
     */
    public function setTierId($tierId) {
        $this->tierId = $tierId;
    }

    /**
     * @return float
     */
    public function getTierPrice() {
        return $this->tierPrice;
    }

    /**
     * @param float $tierPrice
     */
    public function setTierPrice($tierPrice) {
        $this->tierPrice = $tierPrice;
    }

    /**
     * @return string
     */
    public function getCurrencyCode() {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode($currencyCode) {
        $this->currencyCode = $currencyCode;
    }


}
