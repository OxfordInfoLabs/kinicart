<?php


namespace Kinicart\Objects\Cart;


class SimpleSubscriptionCartItem extends SubscriptionCartItem {


    /**
     * Get the main title for this Cart Item.
     *
     * @return string
     */
    public function getTitle() {
        return "Test Subscription";
    }


    public function getSubtitle() {
        // TODO: Implement getSubtitle() method.
    }

    /**
     * Get the description for this Cart Item.
     *
     * @return string
     */
    public function getDescription() {
        // TODO: Implement getDescription() method.
    }

    /**
     * Get the unit price for this Cart Item.
     *
     * @param $currency
     * @param null $tierId
     * @return float
     */
    public function getUnitPrice($currency, $tierId = null) {
        return 10;
    }

    public function getType() {
        return "simplesubscription";
    }
}
