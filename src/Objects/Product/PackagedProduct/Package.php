<?php


namespace Kinicart\Objects\Product\PackagedProduct;

use Kinicart\Objects\Pricing\ProductTierPrice;
use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * Class Package
 * @package Kinicart\Objects\Product
 *
 * @table kc_pp_package
 * @interceptor Kinicart\Objects\Product\PackagedProduct\PackageInterceptor
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
     *
     * @var Package[]
     */
    private $childPackages;


    /**
     * Product prices
     *
     * @oneToMany
     * @childJoinColumns product_identifier,item_identifier
     *
     * @var ProductTierPrice[]
     */
    private $tierPrices;


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
     * Set up default values for tier prices in particular
     *
     * Package constructor.
     */
    public function __construct() {
        $this->tierPrices = [new ProductTierPrice(null, ProductTierPrice::PRICING_ROUND_UP, 0.99)];
    }


    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
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
     * @return ProductTierPrice[]
     */
    public function getTierPrices() {
        return $this->tierPrices;
    }

    /**
     * @param ProductTierPrice[] $tierPrices
     */
    public function setTierPrices($tierPrices) {
        $this->tierPrices = $tierPrices;
    }

    /**
     * @return string
     */
    public function getParentIdentifier() {
        return $this->parentIdentifier;
    }


}
