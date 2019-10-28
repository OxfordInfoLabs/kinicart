<?php

namespace Kinicart\Services\Subscription;

use Kinicart\Objects\Cart\SubscriptionCartItem;
use Kinicart\Objects\Pricing\ProductBasePrice;
use Kinicart\Objects\Subscription\Subscription;
use Kinicart\Types\Recurrence;

/**
 * Handle persistence and workflow functions for subscriptions.
 *
 * Class SubscriptionService
 */
class SubscriptionService {


    /**
     * Create a brand new subscription for an account holder using a supplied cart item
     *
     * @param integer $accountId
     * @param SubscriptionCartItem $subscriptionCartItem
     */
    public function createNewSubscription($accountId, $subscriptionCartItem, $relatedObjectId = null) {

        $subscription = new Subscription($accountId, $subscriptionCartItem, $relatedObjectId);

        $subscription->save();

        // Return the id of the new sub
        return $subscription->getId();

    }

}
