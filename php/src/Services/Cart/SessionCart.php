<?php

namespace Kinicart\Services\Cart;

use Kiniauth\Services\Application\Session;
use Kinicart\Objects\Cart\Cart;
use Kinicart\Objects\Cart\CartItem;
use Kinicart\Services\Account\SessionAccountProvider;
use Kinicart\Services\Pricing\PricingService;

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


    /**
     * @var PricingService
     */
    private $pricingService;


    /**
     * @var SessionAccountProvider
     */
    private $sessionAccountProvider;


    const CART_SESSION_NAME = "__kinicart_cart";

    /**
     * SessionCart constructor.
     * @param Session $session
     * @param PricingService $pricingService
     * @param SessionAccountProvider $sessionAccountProvider
     */
    public function __construct($session, $pricingService, $sessionAccountProvider) {
        $this->session = $session;
        $this->pricingService = $pricingService;
        $this->sessionAccountProvider = $sessionAccountProvider;
    }

    /**
     * @return Session
     */
    public function getSession() {
        return $this->session;
    }


    /**
     * Gets the session cart as a Cart object
     *
     * @return Cart
     */
    public function get() {
        $cart = $this->session->getValue(self::CART_SESSION_NAME);
        if (!$cart) {
            $cart = new Cart($this->sessionAccountProvider);
            $this->session->setValue(self::CART_SESSION_NAME, $cart);
        }
        return $cart;
    }


    /**
     * Convenience function for getting an item at an index.
     *
     * @param $index
     */
    public function getItem($index) {
        $cart = $this->get();
        return $cart->getItem($index);
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
     * Return the number of items
     *
     * @return int
     */
    public function getNumberOfItems() {
        $cart = $this->get();
        return sizeof($cart->getItems());
    }

    /**
     * Clear the session cart and reset the state
     */
    public function clear() {
        $this->session->setValue(self::CART_SESSION_NAME, null);
    }


}
