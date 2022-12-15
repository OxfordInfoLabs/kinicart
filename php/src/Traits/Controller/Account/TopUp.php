<?php


namespace Kinicart\Traits\Controller\Account;


use Kinicart\Objects\Cart\AccountTopUpCartItem;
use Kinicart\Services\Cart\SessionCart;

trait TopUp {


    /**
     * @var SessionCart
     */
    private $sessionCart;


    /**
     * TopUp constructor.
     *
     * @param SessionCart $sessionCart
     */
    public function __construct($sessionCart) {
        $this->sessionCart = $sessionCart;
    }

    /**
     * @http POST /cartitem
     *
     * @param float $amount
     */
    public function addTopUpCartItem($amount) {
        $cartItem = new AccountTopUpCartItem($amount);
        $this->sessionCart->addItem($cartItem);
    }

}