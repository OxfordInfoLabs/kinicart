<?php


namespace Kinicart\Objects\Product\PackagedProduct;

use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * Persisted data for a reusable product feature.  This mainly encapsulates supplier pricing data and
 * margins for sale.
 *
 * Class ProductFeature
 * @package Kinicart\Objects\Product\PackagedProduct
 *
 * @interceptor Kinicart\Objects\Product\PackagedProduct\PackagedProductFeatureInterceptor
 * @table kc_pp_product_feature
 */
class PackagedProductFeature extends ActiveRecord {

    /**
     * Product identifier
     *
     * @var string
     * @primaryKey
     */
    private $productIdentifier;


    /**
     * @var string
     * @primaryKey
     */
    private $featureIdentifier;


    /**
     * The unit buy price of this feature from a supplier if relevant.
     *
     * @var float
     */
    private $unitSupplierBuyPrice;


    /*
     * The unit base margin for this feature for sale.  This + supplier price
     * form a suggested minimum selling price for this feature.
     *
     * @var float
     */
    private $unitBaseMargin;


    /**
     * The working period for which the unit supplier price applies (either MONTHLY or ANNUAL)
     *
     * @var string
     */
    private $workingPeriod;


    /**
     * The currency for which the unit supplier price and base margin are defined
     * as a 3 character currency code.
     *
     * @var string
     */
    private $workingCurrency;


    /**
     * Unmapped temporal member, added in on retrieval for convenience
     *
     * @var Feature
     * @unmapped
     */
    private $feature;


    /**
     * Construct with product identifier and feature
     *
     * @param string $productIdentifier
     * @param Feature $feature
     *
     * PackagedProductFeature constructor.
     */
    public function __construct($productIdentifier, $feature, $unitSupplierBuyPrice = null, $unitBaseMargin = null, $workingPeriod = null, $workingCurrency = null) {
        $this->productIdentifier = $productIdentifier;
        $this->featureIdentifier = $feature ? $feature->getIdentifier() : null;
        $this->feature = $feature;
        $this->unitSupplierBuyPrice = $unitSupplierBuyPrice;
        $this->unitBaseMargin = $unitBaseMargin;
        $this->workingPeriod = $workingPeriod;
        $this->workingCurrency = $workingCurrency;
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
    public function getFeatureIdentifier() {
        return $this->featureIdentifier;
    }

    /**
     * @param string $featureIdentifier
     */
    public function setFeatureIdentifier($featureIdentifier) {
        $this->featureIdentifier = $featureIdentifier;
    }

    /**
     * @return float
     */
    public function getUnitSupplierBuyPrice() {
        return $this->unitSupplierBuyPrice;
    }

    /**
     * @param float $unitSupplierBuyPrice
     */
    public function setUnitSupplierBuyPrice($unitSupplierBuyPrice) {
        $this->unitSupplierBuyPrice = $unitSupplierBuyPrice;
    }

    /**
     * @return mixed
     */
    public function getUnitBaseMargin() {
        return $this->unitBaseMargin;
    }

    /**
     * @param mixed $unitBaseMargin
     */
    public function setUnitBaseMargin($unitBaseMargin) {
        $this->unitBaseMargin = $unitBaseMargin;
    }

    /**
     * @return string
     */
    public function getWorkingPeriod() {
        return $this->workingPeriod;
    }

    /**
     * @param string $workingPeriod
     */
    public function setWorkingPeriod($workingPeriod) {
        $this->workingPeriod = $workingPeriod;
    }


    /**
     * @return string
     */
    public function getWorkingCurrency() {
        return $this->workingCurrency;
    }

    /**
     * @param string $workingCurrency
     */
    public function setWorkingCurrency($workingCurrency) {
        $this->workingCurrency = $workingCurrency;
    }

    /**
     * @return Feature
     */
    public function getFeature() {
        return $this->feature;
    }

    /**
     * @param Feature $feature
     */
    public function setFeature($feature) {
        $this->feature = $feature;
    }


}
