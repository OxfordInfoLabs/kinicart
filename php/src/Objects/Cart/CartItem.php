<?php


namespace Kinicart\Objects\Cart;


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
     * Get the unit price for this Cart Item.
     *
     * @param $currency
     * @param null $tierId
     * @return float
     */
    public abstract function getUnitPrice($currency, $tierId = null);


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
