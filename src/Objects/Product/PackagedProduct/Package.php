<?php


namespace Kinicart\Objects\Product\PackagedProduct;

/**
 * Class Package
 * @package Kinicart\Objects\Product
 *
 * @table kc_pp_package
 */
class Package {

    /**
     * @var integer
     */
    private $id;


    /**
     * The unique product identifier for the related packaged product.
     *
     * @var string
     */
    private $productIdentifier;


    /**
     * Package type, one of the class constants - currently either
     * PLAN or ADD_ON
     *
     * @var string
     */
    private $type;


//    /**
//     * Child packages - these are for e.g. Add ons which may be tightly
//     * scoped to a plan.
//     *
//     * @oneToMany
//     * @childJoinColumns parent_package_id
//     *
//     * @var Package[]
//     */
//    private $childPackages;

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
     * @childJoinColumns package_id
     *
     * @var PackageFeature[]
     */
    private $features;


    /**
     * The base sale price before any tier based markup is added.
     *
     * @var float
     */
    private $baseSalePrice;


    /**
     * The currency for which the base sale price has been defined (3 character currency code).
     *
     * @var string
     */
    private $baseSaleCurrency;

    // Package type constants.
    const TYPE_PLAN = "PLAN";
    const TYPE_ADD_ON = "ADD_ON";


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
     * @return float
     */
    public function getBaseSalePrice() {
        return $this->baseSalePrice;
    }

    /**
     * @param float $baseSalePrice
     */
    public function setBaseSalePrice($baseSalePrice) {
        $this->baseSalePrice = $baseSalePrice;
    }

    /**
     * @return string
     */
    public function getBaseSaleCurrency() {
        return $this->baseSaleCurrency;
    }

    /**
     * @param string $baseSaleCurrency
     */
    public function setBaseSaleCurrency($baseSaleCurrency) {
        $this->baseSaleCurrency = $baseSaleCurrency;
    }


}
