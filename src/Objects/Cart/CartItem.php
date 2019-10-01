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
    private $quantity;


    /**
     * Array of sub cart items.
     *
     * @var CartItem[]
     */
    private $subItems;


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
     * @return string
     */
    public abstract function getUnitPrice();


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

    /**
     * @return CartItem[]
     */
    public function getSubItems() {
        return $this->subItems;
    }

    /**
     * @param CartItem[] $subItems
     */
    public function setSubItems($subItems) {
        $this->subItems = $subItems;
    }


}
