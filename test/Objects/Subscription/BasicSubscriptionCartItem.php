<?php


namespace Kinicart\Objects\Subscription;


class BasicSubscriptionCartItem extends SubscriptionCartItem {

    /**
     * Get the main title for this Cart Item.
     *
     * @return string
     */
    public function getTitle() {
        return "Basic";
    }

    /**
     * Get the description for this Cart Item.
     *
     * @return string
     */
    public function getDescription() {
        return "Basic Subscription Cart Item";
    }

    /**
     * Get the unit price for this Cart Item.
     *
     * @param $currency
     * @param null $tierId
     * @return float
     */
    public function getUnitPrice($currency, $tierId = null) {
        return 10.00;
    }
}
