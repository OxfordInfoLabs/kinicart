<?php


namespace Kinicart\Objects\Cart;

/**
 * Product Cart item
 *
 * Class ProductCartItem
 *
 * @package Kinicart\Objects\Cart
 */
abstract class ProductCartItem extends CartItem {

    /**
     * String product identifier.
     *
     * @var string
     */
    protected $productIdentifier;


    /**
     * ProductCartItem constructor.
     *
     * @param string $productIdentifier
     */
    public function __construct($productIdentifier) {
        $this->productIdentifier = $productIdentifier;
    }


    /**
     * @return string
     */
    public function getProductIdentifier() {
        return $this->productIdentifier;
    }


}
