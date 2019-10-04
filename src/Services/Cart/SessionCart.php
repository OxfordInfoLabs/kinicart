<?php

namespace Kinicart\Services\Cart;

use Kiniauth\Services\Application\Session;
use Kinicart\Objects\Cart\Cart;
use Kinicart\Objects\Cart\CartItem;

/**
 * Process cart operations including
 *
 * @package Kinicart\Services\Cart
 * @noProxy
 */
class SessionCart {

    /**
     * @var Session
     */
    private $session;


    const CART_SESSION_NAME = "__kinicart_cart";

    /**
     * SessionCart constructor.
     * @param Session $session
     */
    public function __construct($session) {
        $this->session = $session;
    }

    /**
     * Gets the session cart as a Cart object
     *
     * @return Cart
     */
    public function get() {
        $cart = $this->session->getValue(self::CART_SESSION_NAME);
        if (!$cart) {
            $cart = new Cart();
            $this->session->setValue(self::CART_SESSION_NAME, $cart);
        }
        return $cart;
    }


    /**
     * Add an item to the session cart.
     *
     * @param CartItem $cartItem
     */
    public function addItem($cartItem) {
        $cart = $this->get();
        $cart->addItem($cartItem);
        $this->session->setValue(self::CART_SESSION_NAME, $cart);
    }


    /**
     * Update an item with a new one.
     *
     * @param $index
     * @param $updatedCartItem
     */
    public function updateItem($index, $updatedCartItem) {
        $cart = $this->get();
        $cart->updateItem($index, $updatedCartItem);
        $this->session->setValue(self::CART_SESSION_NAME, $cart);
    }


    /**
     * Remove the item at the specified index.
     *
     * @param $index
     */
    public function removeItem($index) {
        $cart = $this->get();
        $cart->removeItem($index);
        $this->session->setValue(self::CART_SESSION_NAME, $cart);
    }

    /**
     * Clear the session cart and reset the state
     */
    public function clear() {
        $this->session->setValue(self::CART_SESSION_NAME, null);
    }


}
