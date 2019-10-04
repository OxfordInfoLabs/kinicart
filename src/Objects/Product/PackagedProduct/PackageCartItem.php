<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinicart\Objects\Cart\CartItem;

class PackageCartItem extends CartItem {

    /**
     * @var Package
     */
    private $package;

    /**
     * Construct a package cart item with a package.
     *
     * PackageCartItem constructor.
     * @param $package
     */
    public function __construct($package) {
        $this->package = $package;
    }


    /**
     * Get the main title for this Cart Item.
     *
     * @return string
     */
    public function getTitle() {

    }

    /**
     * Get the description for this Cart Item.
     *
     * @return string
     */
    public function getDescription() {

    }

    /**
     * Get the unit price for this Cart Item in supplied currency.
     *
     * @param $currency
     * @return string
     */
    public function getUnitPrice($currency) {

    }
}
