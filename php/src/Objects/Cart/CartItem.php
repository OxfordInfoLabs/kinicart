<?php


namespace Kinicart\Objects\Cart;


use Kinicart\Objects\Account\Account;

/**
 * Class CartItem
 * @package Kinicart\Objects\Cart
 */
abstract class CartItem {

    /**
     * @var integer
     */
    private $quantity = 1;

    /**
     * Type string
     *
     * @return mixed
     */
    public abstract function getType();


    /**
     * Subtype - optional identifier if required by a cart item.
     *
     * @return mixed
     */
    public function getSubType() {
        return null;
    }


    /**
     * Get the main title for this Cart Item.
     *
     * @return string
     */
    public abstract function getTitle();


    /**
     * Get the subtitle for this cart item.
     *
     * @return mixed
     */
    public abstract function getSubtitle();


    /**
     * Get the description for this Cart Item.
     *
     * @return string
     */
    public abstract function getDescription();


    /**
     * Boolean indicator as to whether or not this cart item is taxable
     *
     * @return bool
     */
    public function isTaxable() {
        return true;
    }


    /**
     * Get the unit price for this Cart Item.
     *
     * @param $currency
     * @param null $tierId
     * @return float
     */
    public abstract function getUnitPrice($currency, $tierId = null);


    /**
     * On complete method, called once the cart payment has completed to perform any
     * item specific operations.
     *
     * @param Account $account
     */
    public abstract function onComplete($account);


    /**
     * Return any sub items if required.
     *
     * @return CartItem[]
     */
    public function getSubItems() {
        return [];
    }


    /**
     * @return int
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }


}
