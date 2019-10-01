<?php

namespace Kinicart\Objects\Cart;

/**
 * Class Cart
 *
 * @noGenerate
 */
class Cart {

    /**
     * @var CartItem[]
     */
    private $items;

    /**
     * Get the array of cart items in use by this cart.
     *
     * @return CartItem[]
     */
    public function getItems() {
        return $this->items;
    }

}
