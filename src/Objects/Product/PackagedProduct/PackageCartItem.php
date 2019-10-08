<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinicart\Objects\Cart\CartItem;
use Kinicart\Objects\Cart\SimpleCartItem;

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
        return $this->package->getTitle();
    }

    /**
     * Get the description for this Cart Item.
     *
     * @return string
     */
    public function getDescription() {
        return $this->package->getDescription();
    }

    /**
     * Get the unit price for this Cart Item in supplied currency.
     *
     * @param $currency
     * @return string
     */
    public function getUnitPrice($currency) {

    }

    /**
     * Get sub items for this package cart item
     *
     * @return CartItem[]
     */
    public function getSubItems() {
        $subItems = [];
        foreach ($this->package->getFeatures() ?? [] as $feature) {
            $subItems[] = new SimpleCartItem($feature->getFeatureIdentifier() ? $feature->getProductFeature()->getFeature()->getTitle() : $feature->getTitle(),
                $feature->getFeatureIdentifier() ? $feature->getProductFeature()->getFeature()->getDescription() : $feature->getDescription());
        }

        return $subItems;
    }


}
