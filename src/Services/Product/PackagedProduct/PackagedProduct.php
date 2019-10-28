<?php

namespace Kinicart\Services\Product\PackagedProduct;


use Kinicart\Objects\Cart\SubscriptionCartItem;
use Kinicart\Objects\Product\PackagedProduct\Feature;
use Kinicart\Services\Product\SubscriptionProduct;

/**
 * Base class for a product, should be extended to make concrete application products.
 *
 * Class Product
 */
abstract class PackagedProduct extends SubscriptionProduct {

    /**
     * @var PackagedProductService
     */
    private $packagedProductService;


    /**
     * Also inject the packaged product service.
     *
     * PackagedProduct constructor.
     * @param $subscriptionService
     * @param $packagedProductService
     */
    public function __construct($subscriptionService, $packagedProductService) {
        parent::__construct($subscriptionService);
        $this->packagedProductService = $packagedProductService;
    }

    /**
     * Get a list of features which this product makes available.  Features are combined
     * into packages for sale purposes.
     *
     * @return Feature[]
     */
    public abstract function getFeatures();


    /**
     * Override the default behaviour to ensure that we create / update
     * subscription package entries as required.
     *
     * @param \Kinicart\Objects\Account\Account $account
     * @param \Kinicart\Objects\Cart\SubscriptionCartItem $cartItem
     * @return int
     */
    public function processCartItem($account, $cartItem) {
        $subscriptionId = parent::processCartItem($account, $cartItem);

        // If new / adjustment, sync subscription package entries.
        if ($cartItem->getOperation() != SubscriptionCartItem::OPERATION_RENEW) {
            $this->packagedProductService->saveSubscriptionPackages($subscriptionId, $cartItem);
        }

        return $subscriptionId;

    }


}
