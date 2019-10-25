<?php


namespace Kinicart\Objects\Subscription;


use Kinicart\Objects\Product\Product;


/**
 * Extension of product interface for subscription based products.
 */
interface SubscriptionProduct extends Product {


    /**
     * Product specific logic available when a new subscription is about to be activated.
     * This typically returns the id of a related object for this product type to be associated with the subscription.
     *
     * @param SubscriptionCartItem $cartItem
     * @return integer
     */
    public function subscriptionActivation($cartItem);


    /**
     * Product specific logic available when a subscription is renewed.
     *
     * @param Subscription $subscription
     *
     */
    public function subscriptionRenewed($subscription);


    /**
     * Product specific logic available when a subscription is suspended.
     *
     * @param Subscription $subscription
     */
    public function subscriptionSuspended($subscription);


    /**
     * Product specific logic available when a subscription is cancelled.
     *
     * @param Subscription $subscription
     */
    public function subscriptionCancelled($subscription);

}
