<?php

namespace Kinicart\Services\Subscription;

use Kinicart\Objects\Pricing\ProductBasePrice;
use Kinicart\Objects\Subscription\Subscription;

/**
 * Handle persistence and workflow functions for subscriptions.
 *
 * Class SubscriptionService
 */
class SubscriptionService {


    /**
     * Create a brand new subscription for an account holder.
     *
     * @param $accountId
     * @param $description
     * @param $productIdentifier
     * @param $relatedObjectId
     * @param $recurrenceType
     */
    public function createNewSubscription($accountId, $description, $productIdentifier, $relatedObjectId, $recurrenceType = ProductBasePrice::RECURRENCE_MONTHLY,
                                          $recurrence = 1, $numberOfRenewals = null) {

        $subscription = new Subscription($accountId, $description, $productIdentifier, $relatedObjectId, $recurrenceType,
            $recurrence, $numberOfRenewals);

        $subscription->save();

        // Return the id of the new sub
        return $subscription->getId();

    }

}
