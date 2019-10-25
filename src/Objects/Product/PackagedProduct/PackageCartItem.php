<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinicart\Objects\Cart\CartItem;
use Kinicart\Objects\Cart\SimpleCartItem;
use Kinicart\Objects\Pricing\ProductBasePrice;

/**
 */
class PackageCartItem extends CartItem {

    /**
     * @var Package
     */
    private $package;


    /**
     * Recurrence type
     *
     * @var string
     */
    private $recurrenceType;


    /**
     * Construct a package cart item with a package.
     *
     * PackageCartItem constructor.
     * @param $package
     */
    public function __construct($package, $recurrenceType = ProductBasePrice::RECURRENCE_MONTHLY) {
        $this->package = $package;
        $this->recurrenceType = $recurrenceType;

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
     * @param null $tierId
     * @return string
     */
    public function getUnitPrice($currency, $tierId = null) {
        return $this->package->getTierPrice($tierId, $this->recurrenceType, $currency);
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


    public function process() {
        // TODO: Implement process() method.
    }
}
