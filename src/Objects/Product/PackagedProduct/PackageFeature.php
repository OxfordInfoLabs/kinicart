<?php


namespace Kinicart\Objects\Product\PackagedProduct;

/**
 * A feature which has been added to a package - along with base pricing information
 *
 * Class PackageFeature
 * @package Kinicart\Objects\Product\PackagedProduct
 *
 * @table kc_pp_package_feature
 */
class PackageFeature {

    /**
     * Primary key
     *
     * @var integer
     */
    private $id;


    /**
     * The identifier of this feature within the product - only used if this is a core feature
     *
     * @var string
     */
    private $featureIdentifier;


    /**
     * The title of this feature - only used if this is an adhoc feature.
     *
     * @var string
     */
    private $title;

    /**
     * The full description of this feature - only used if this is an adhoc feature.
     *
     * @var string
     */
    private $description;


    /**
     * The quantity of this feature which is being utilised in the package.
     *
     * @var float
     */
    private $quantity = 1;


    /**
     * Unmapped temporal member, added in on retrieval for convenience
     *
     * @var Feature
     * @unmapped
     */
    private $feature;

    /**
     * PackageFeature constructor.
     * @param string $featureIdentifier
     * @param string $title
     * @param string $description
     * @param float $quantity
     */
    public function __construct($featureIdentifier, $title, $description, $quantity) {
        $this->featureIdentifier = $featureIdentifier;
        $this->title = $title;
        $this->description = $description;
        $this->quantity = $quantity;
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
     * @return float
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
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
