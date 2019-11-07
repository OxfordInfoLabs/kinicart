<?php


namespace Kinicart\Objects\Product\PackagedProduct;

use Kinicart\Exception\Pricing\MissingProductPriceException;
use Kinicart\Objects\Pricing\ProductBasePrice;
use Kinicart\Objects\Pricing\ProductTierPrice;
use Kinicart\Services\Pricing\PricingService;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * Class Package
 * @package Kinicart\Objects\Product
 *
 * @table kc_pp_package
 * @generate
 */
class Package extends ActiveRecord {


    /**
     * The unique product identifier for the related packaged product.
     *
     * @var string
     * @primaryKey
     */
    private $productIdentifier;


    /**
     * @var string
     * @primaryKey
     */
    private $identifier;


    /**
     * Package type, one of the class constants - currently either
     * PLAN or ADD_ON
     *
     * @var string
     */
    private $type;


    /**
     * The descriptive title for this package
     *
     * @var string
     */
    private $title;


    /**
     * A more advanced description for this package.
     *
     * @var string
     */
    private $description;


    /**
     * @oneToMany
     * @childJoinColumns product_identifier,package_identifier
     *
     * @var PackageFeature[]
     */
    private $features;


    /**
     * The order in which this package fits in the upgrade hierarchy for this product (used for plans).
     *
     * @var integer
     */
    private $upgradeOrder;


    /**
     * Child packages - these are for e.g. Add ons which may be tightly
     * scoped to a plan.
     *
     * @oneToMany
     * @childJoinColumns parent_product_identifier,parent_identifier
     * @maxDepth 2
     *
     * @var Package[]
     */
    private $childPackages;


    /**
     * Product base prices
     *
     * @oneToMany
     * @childJoinColumns product_identifier,item_identifier
     *
     * @var ProductBasePrice[]
     */
    private $prices;


    /**
     * Reference to the parent identifier if this child is a sub package.
     *
     * @var string
     */
    private $parentIdentifier;


    // Package type constants.
    const TYPE_PLAN = "PLAN";
    const TYPE_ADD_ON = "ADD_ON";

    /**
     * Package constructor.
     *
     * @param string $productIdentifier
     * @param string $identifier
     */
    public function __construct($productIdentifier = null, $identifier = null) {
        $this->productIdentifier = $productIdentifier;
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier) {
        $this->identifier = $identifier;
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
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
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
     * @return PackageFeature[]
     */
    public function getFeatures() {
        return $this->features;
    }

    /**
     * @param PackageFeature[] $features
     */
    public function setFeatures($features) {
        $this->features = $features;
    }

    /**
     * @return Package[]
     */
    public function getChildPackages() {
        return $this->childPackages;
    }

    /**
     * @param Package[] $childPackages
     */
    public function setChildPackages($childPackages) {
        $this->childPackages = $childPackages;
    }

    /**
     * @return int
     */
    public function getUpgradeOrder() {
        return $this->upgradeOrder;
    }

    /**
     * @param int $upgradeOrder
     */
    public function setUpgradeOrder($upgradeOrder) {
        $this->upgradeOrder = $upgradeOrder;
    }

    /**
     * @return ProductBasePrice[]
     */
    public function getPrices() {
        return $this->prices;
    }

    /**
     * @param ProductBasePrice[] $prices
     */
    public function setPrices($prices) {
        $this->prices = $prices;
    }


    /**
     * Get a tier price for this package in the supplied currency for the supplied recurrence type.
     * - If no base pricing has been set this will return 0.
     * - If no tier prices have been defined, they will be calculated using base pricing with multipliers and round-ups and currency conversions if required.
     * - If tier prices have been explicitly defined they will be used and currency converted if required.
     * @param $tierId
     * @param $recurrenceType
     * @param $currency
     * @return float
     */
    public function getTierPrice($tierId, $recurrenceType, $currency) {

        // If we have prices, continue
        if ($this->prices) {
            foreach ($this->prices as $price) {
                if ($price->getRecurrenceType() == $recurrenceType) {
                    return $price->getTierPrice($currency, $tierId);
                }
            }
        } else {
            throw new MissingProductPriceException("No prices have been defined for the package $this->title of type $this->productIdentifier");
        }

        return 0;


    }


    /**
     * Get all tier prices - if just a currency is supplied this will be an array indexed by
     * recurrence type and then tierId.  If either of the other two are supplied this will become
     * a single array indexed by the other one - else it will be a single float value
     *
     * @param $currencyCode
     * @param string $recurrenceType
     * @param integer $tierId
     *
     * @return mixed
     */
    public function getAllTierPrices($currencyCode) {

        // Ensure we have prices.
        if (!$this->prices) {
            throw new MissingProductPriceException("No prices have been defined for the package $this->title of type $this->productIdentifier");
        }

        $allTiers = Container::instance()->get(PricingService::class)->getTiers();

        /**
         * Now loop through each price we get back.
         */
        $returnedPrices = [];
        foreach ($this->prices as $price) {
            $returnedPrices[$price->getRecurrenceType()] = [];
            foreach ($allTiers as $tier) {
                $returnedPrices[$price->getRecurrenceType()][$tier->getId()] = $price->getTierPrice($currencyCode, $tier->getId());
            }
        }

        return $returnedPrices;
    }


    /**
     * @return string
     */
    public function getParentIdentifier() {
        return $this->parentIdentifier;
    }


}
