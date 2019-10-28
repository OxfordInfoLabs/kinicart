<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * Encodes the current packages which form part of a subscription
 *
 * @generate
 * @table kc_pp_subscription_package
 */
class PackagedProductSubscriptionPackage extends ActiveRecord {


    /**
     * @var integer
     * @primaryKey
     */
    private $subscriptionId;


    /**
     * @var string
     * @primaryKey
     */
    private $productIdentifier;

    /**
     * @var string
     * @primaryKey
     */
    private $packageIdentifier;


    /**
     *
     * @manyToOne
     * @parentJoinColumns product_identifier,package_identifier
     * @readOnly
     *
     * @var Package
     */
    private $package;


    /**
     * Create a new subscription package mapping
     *
     * PackagedProductSubscriptionPackage constructor.
     */
    public function __construct($subscriptionId, $productIdentifier, $packageIdentifier) {
        $this->subscriptionId = $subscriptionId;
        $this->productIdentifier = $productIdentifier;
        $this->packageIdentifier = $packageIdentifier;
    }

    /**
     * @return int
     */
    public function getSubscriptionId() {
        return $this->subscriptionId;
    }

    /**
     * @return int
     */
    public function getProductIdentifier() {
        return $this->productIdentifier;
    }

    /**
     * @return int
     */
    public function getPackageIdentifier() {
        return $this->packageIdentifier;
    }


    /**
     * @return Package
     */
    public function getPackage() {
        return $this->package;
    }


}
