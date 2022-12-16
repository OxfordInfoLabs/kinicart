<?php

namespace Kinicart\Objects\Cart;

use Kinicart\Exception\Cart\CartItemDoesNotExistsException;
use Kinicart\Services\Account\AccountProvider;

/**
 * Class Cart
 *
 */
class Cart {

    /**
     * @var CartItem[]
     */
    private $items = [];


    /**
     * An account provider for the account.  This enables
     * the underlying data to change as users log in / out etc.
     *
     * @var AccountProvider
     */
    private $accountProvider;

    /**
     * Cart constructor.
     *
     * Pass an account provider
     *
     * @param AccountProvider $accountProvider
     */
    public function __construct($accountProvider) {
        $this->accountProvider = $accountProvider;
    }


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
     * Get the cart item at the passed index or throw exception.
     *
     * @param $index
     * @return CartItem
     */
    public function getItem($index) {
        if (!isset($this->items[$index]))
            throw new CartItemDoesNotExistsException($index);

        return $this->items[$index];

    }

    /**
     * Update / Replace a cart item at the passed index
     *
     * @param integer $index
     * @param CartItem $updatedCartItem
     */
    public function updateItem($index, $updatedCartItem) {
        $this->getItem($index);
        array_splice($this->items, $index, 1, [$updatedCartItem]);
    }


    /**
     * Remove an item at the passed index.
     *
     * @param integer $index
     * @param CartItem $updatedCartItem
     */
    public function removeItem($index) {
        $this->getItem($index);
        array_splice($this->items, $index, 1);
    }


    /**
     * Get the cart total based upon the constructed Account Provider
     *
     * @return float
     */
    public function getSubTotal() {

        $account = $this->accountProvider->provideAccount();

        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getUnitPrice($account->getAccountData()->getCurrencyCode(), $account->getAccountData()->getTierId());
        }

        return number_format(round($total, 2), 2, '.', '');

    }


    /**
     * Get the taxes if applicable
     */
    public function getTaxes() {
        $account = $this->accountProvider->provideAccount();

        $taxes = 0;
        foreach ($this->items as $item) {
            if ($item->isTaxable())
                $taxes += $item->getUnitPrice($account->getAccountData()->getCurrencyCode(), $account->getAccountData()->getTierId()) * 0.2;
        }

        return number_format(round($taxes, 2), 2, '.', '');
    }


    /**
     * Get the total including taxes
     */
    public function getTotal() {
        return number_format(round($this->getSubTotal() + $this->getTaxes(), 2), 2, '.', '');
    }


    /**
     * @return AccountProvider
     */
    public function getAccountProvider() {
        return $this->accountProvider;
    }

}
