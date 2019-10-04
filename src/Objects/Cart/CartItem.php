<?php


namespace Kinicart\Objects\Cart;


/**
 * @noGenerate
 *
 * Class CartItem
 * @package Kinicart\Objects\Cart
 */
abstract class CartItem {

    /**
     * @var integer
     */
    private $quantity = 1;


    /**
     * Get the main title for this Cart Item.
     *
     * @return string
     */
    public abstract function getTitle();


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
     * @return string
     */
    public abstract function getUnitPrice($currency);


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
