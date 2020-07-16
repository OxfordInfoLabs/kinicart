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
     * @maxLength 50
     */
    private $productIdentifier;

    /**
     * @var string
     * @primaryKey
     * @maxLength 50
     */
    private $packageIdentifier;


    /**
     * The quantity of this package which has been added to the subscription.
     * This is only really applicable to ADD ON packages.
     *
     * @var integer
     */
    private $quantity = 1;


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
    public function __construct($subscriptionId, $productIdentifier, $packageIdentifier, $quantity = 1) {
        $this->subscriptionId = $subscriptionId;
        $this->productIdentifier = $productIdentifier;
        $this->packageIdentifier = $packageIdentifier;
        $this->quantity = $quantity;
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

    /**
     * @return int
     */
    public function getQuantity() {
        return $this->quantity;
    }


}
