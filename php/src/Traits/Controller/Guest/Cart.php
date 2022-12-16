<?php


namespace Kinicart\Traits\Controller\Guest;


use Kinicart\Services\Cart\SessionCart;
use Kinicart\ValueObjects\Cart\CartSummary;
use Kinikit\Core\DependencyInjection\Container;

trait Cart {

    /**
     * @var SessionCart
     */
    private $sessionCart;

    /**
     * Cart constructor.
     * @param SessionCart $sessionCart
     */
    public function __construct($sessionCart) {
        $this->sessionCart = $sessionCart;
    }

    /**
     * Get the current session cart
     *
     * @http GET /
     *
     * @return CartSummary
     */
    public function getCart() {
        return new CartSummary($this->sessionCart->get());
    }

    /**
     * Remove the cart item at the specified index
     *
     * @http GET /remove
     *
     * @param $index
     */
    public function removeItem($index) {
        $this->sessionCart->removeItem($index);
    }

}
