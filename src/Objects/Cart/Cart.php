<?php

namespace Kinicart\Objects\Cart;

use Kinicart\Exception\Cart\CartItemDoesNotExistsException;

/**
 * Class Cart
 *
 * @noGenerate
 */
class Cart {

    /**
     * @var CartItem[]
     */
    private $items = [];

    /**
     * Get the array of cart items in use by this cart.
     *
     * @return CartItem[]
     */
    public function getItems() {
        return $this->items;
    }


    /**
     * Add a cart item
     *
     * @param CartItem $cartItem
     */
    public function addItem($cartItem) {
        $this->items[] = $cartItem;
    }


    /**
     * Update / Replace a cart item at the passed index
     *
     * @param integer $index
     * @param CartItem $updatedCartItem
     */
    public function updateItem($index, $updatedCartItem) {

        if (!isset($this->items[$index]))
            throw new CartItemDoesNotExistsException($index);

        array_splice($this->items, $index, 1, [$updatedCartItem]);
    }


    /**
     * Remove an item at the passed index.
     *
     * @param integer $index
     * @param CartItem $updatedCartItem
     */
    public function removeItem($index) {

        if (!isset($this->items[$index]))
            throw new CartItemDoesNotExistsException($index);

        array_splice($this->items, $index, 1);
    }

}
