<?php


namespace Kinicart\Objects\Product\PackagedProduct;


class ConcretePackagedProductCartItem extends PackagedProductCartItem {


    /**
     * Get the main title for this Cart Item.
     *
     * @return string
     */
    public function getTitle() {
        return "Test Product";
    }

    /**
     * Get the description for this Cart Item.
     *
     * @return string
     */
    public function getDescription() {
        return "Test Description";
    }
}
