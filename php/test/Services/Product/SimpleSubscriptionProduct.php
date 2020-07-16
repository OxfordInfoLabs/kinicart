<?php


namespace Kinicart\Services\Product;


class SimpleSubscriptionProduct extends SubscriptionProduct {

    /**
     * Get the title for this product
     *
     * @return string
     */
    public function getTitle() {
        return "Simple Sub";
    }

    /**
     * Get the description for this product
     *
     * @return string
     */
    public function getDescription() {
        return "Simple Subscription Product";
    }


    /**
     * Activate a sub and return a new related object id.
     *
     * @param $account
     * @param \Kinicart\Objects\Cart\SubscriptionCartItem $cartItem
     * @return int|void
     */
    public function subscriptionActivation($account, $cartItem) {

        return 100;

    }


}

