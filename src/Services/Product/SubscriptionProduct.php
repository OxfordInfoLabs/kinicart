<?php


namespace Kinicart\Services\Product;

use Kinicart\Objects\Cart\SubscriptionCartItem;
use Kinicart\Objects\Subscription\Subscription;

/**
 * Extension of product interface for subscription based products.
 */
abstract class SubscriptionProduct implements Product {


    /**
     * Process a cart item - call the appropriate method below according to
     * the type of cart item being passed.
     *
     * @param $accountId
     * @param SubscriptionCartItem $cartItem
     * @return mixed|void
     */
    public function processCartItem($accountId, $cartItem) {

    }


    /**
     * Product specific activation logic available when a new subscription is about to be activated.
     * This typically returns the id of a related object for this product type to be associated with the subscription.
     *
     * @param SubscriptionCartItem $cartItem
     * @return integer
     */
    public function subscriptionActivation($cartItem) {

    }


    /**
     * Get the cart items required to fulfil a renewal for a given subscription
     *
     * @param Subscription $subscription
     * @return SubscriptionCartItem[]
     */
    public function getRenewalCartItems($subscription) {

    }


    /**
     * Optional product specific logic available when a subscription is renewed.
     *
     * @param Subscription $subscription
     *
     */
    public function subscriptionRenewed($subscription) {
    }


    /**
     * Optional product specific logic available when a subscription is adjusted.
     *
     * @param Subscription $subscription
     */
    public function subscriptionAdjusted($subscription) {

    }


    /**
     * Optional Product specific logic available when a subscription is suspended.
     *
     * @param Subscription $subscription
     */
    public function subscriptionSuspended($subscription) {

    }


    /**
     * Product specific logic available when a subscription is cancelled.
     *
     * @param Subscription $subscription
     */
    public function subscriptionCancelled($subscription) {

    }


}
