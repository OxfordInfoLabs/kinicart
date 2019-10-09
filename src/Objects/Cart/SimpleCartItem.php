<?php


namespace Kinicart\Objects\Cart;

/**
 * @noGenerate
 *
 * Class SimpleCartItem
 * @package Kinicart\Objects\Cart
 */
class SimpleCartItem extends CartItem {

    /**
     * The title for this cart item
     *
     * @var string
     */
    private $title;

    /**
     * Optional full description for this cart item.
     *
     * @var string
     */
    private $description;


    /**
     * Optional float array of currency based prices
     * keyed in by 3 character currency code if required.
     *
     * @var float[string]
     */
    private $prices;


    /**
     * Array of sub items for this cart item if required.
     *
     * @var CartItem[]
     */
    private $subItems = [];

    /**
     * Simple cart item -  accepts a title and description explicitly and optionally some prices
     * which should be Currency => Value key value pairs.  If only one currency is supplied, others
     * will be inferred using currency conversion.  If no pricing supplied, zero will be returned (assumed to be no cost item).
     *
     * SimpleCartItem constructor.
     * @param string $title
     * @param string $description
     * @param float[string] $prices
     * @param CartItem[] $subItems
     */
    public function __construct($title, $description = null, $prices = [], $subItems = []) {
        $this->title = $title;
        $this->description = $description;
        $this->prices = $prices;
        $this->subItems = $subItems;
    }

    /**
     * Get the main title for this Cart Item.
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Get the description for this Cart Item.
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Get the unit price for this Cart Item.
     *
     * @param $currency
     * @param null $tierId
     * @return string
     */
    public function getUnitPrice($currency, $tierId = null) {
        return isset($this->prices[$currency]) ? $this->prices[$currency] : 0;
    }

    /**
     * Return any sub items.
     *
     * @return CartItem[]
     */
    public function getSubItems() {
        return $this->subItems;
    }


}
