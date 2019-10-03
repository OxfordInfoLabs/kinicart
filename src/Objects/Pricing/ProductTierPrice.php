<?php


namespace Kinicart\Objects\Pricing;

/**
 * Tier price for a product.
 *
 * @table kc_product_tier_price
 *
 */
class ProductTierPrice {

    /**
     * The product identifier for this product
     *
     * @var string
     * @primaryKey
     */
    private $productIdentifier;


    /**
     * The item identifier for this product - this
     * is specific to the implementation for the given product.
     *
     * @var string
     * @primaryKey
     */
    private $itemIdentifier;


    /**
     * If this price is scoped to a tier, this will be used.
     *
     * @var integer
     * @primaryKey
     */
    private $tierId;


    /**
     * Which pricing rule is in place for this product price.
     *
     * @var string
     * @required
     */
    private $pricingRule;


    /**
     * Decimal value qualifying the pricing rule in place for this product price.
     *
     * @var float
     */
    private $value;


    /**
     * An optional currency code (used when pricing rule is FIXED)
     * to allow fixed prices in all currencies.
     *
     * @var string
     */
    private $currencyCode;


    // Pricing rules for the system
    const PRICING_RULE = "RAW";
    const PRICING_ROUND_UP = "ROUND_UP";
    const PRICING_FIXED = "FIXED";

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
     * @return string
     */
    public function getProductIdentifier() {
        return $this->productIdentifier;
    }

    /**
     * @param string $productIdentifier
     */
    public function setProductIdentifier($productIdentifier) {
        $this->productIdentifier = $productIdentifier;
    }

    /**
     * @return string
     */
    public function getItemIdentifier() {
        return $this->itemIdentifier;
    }

    /**
     * @param string $itemIdentifier
     */
    public function setItemIdentifier($itemIdentifier) {
        $this->itemIdentifier = $itemIdentifier;
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
     * @return string
     */
    public function getPricingRule() {
        return $this->pricingRule;
    }

    /**
     * @param string $pricingRule
     */
    public function setPricingRule($pricingRule) {
        $this->pricingRule = $pricingRule;
    }

    /**
     * @return float
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue($value) {
        $this->value = $value;
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
