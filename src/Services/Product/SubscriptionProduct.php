<?php


namespace Kinicart\Services\Product;

use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Cart\SubscriptionCartItem;
use Kinicart\Objects\Subscription\Subscription;
use Kinicart\Services\Subscription\SubscriptionService;

/**
 * Extension of product interface for subscription based products.
 */
abstract class SubscriptionProduct implements Product {

    /**
     * @var SubscriptionService
     */
    private $subscriptionService;


    /**
     * SubscriptionProduct constructor.
     *
     * @param SubscriptionService $subscriptionService
     */
    public function __construct($subscriptionService) {
        $this->subscriptionService = $subscriptionService;
    }


    /**
     * Process a cart item - call the appropriate method below according to
     * the type of cart item being passed.
     *
     * @param Account $account
     * @param SubscriptionCartItem $cartItem
     * @return integer
     */
    public function processCartItem($account, $cartItem) {

        // If a brand new subscription, call activation and then create a new sub
        if ($cartItem->getOperation() == SubscriptionCartItem::OPERATION_NEW) {
            $relatedObjectId = $this->subscriptionActivation($cartItem);
            return $this->subscriptionService->createNewSubscription($account, $cartItem, $relatedObjectId);
        }

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
