<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinicart\Objects\Subscription\Subscription;


/**
 * Class PackagedProductSubscription
 *
 * @table kc_subscription
 * @package Kinicart\Objects\Product\PackagedProduct
 */
class PackagedProductSubscription extends Subscription {

    /**
     * @oneToMany
     * @childJoinColumns subscription_id
     *
     * @var PackagedProductSubscriptionPackage[]
     */
    private $subscriptionPackages;

    /**
     * @return PackagedProductSubscriptionPackage[]
     */
    public function getSubscriptionPackages() {
        return $this->subscriptionPackages;
    }


}
