<?php


namespace Kinicart\Exception\Product\PackagedProduct;


class MaximumAddOnQuantityExceededException extends \Exception {

    /**
     * NoSuchProductPlanException constructor.
     */
    public function __construct($addOnIdentifier, $productIdentifier, $planIdentifier) {
        parent::__construct("You have attempted to add too many add-ons of type $addOnIdentifier for the product of type $productIdentifier and plan $planIdentifier");
    }

}
