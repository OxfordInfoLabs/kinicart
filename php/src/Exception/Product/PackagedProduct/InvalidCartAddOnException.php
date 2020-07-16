<?php


namespace Kinicart\Exception\Product\PackagedProduct;


class InvalidCartAddOnException extends \Exception {

    /**
     * NoSuchProductPlanException constructor.
     */
    public function __construct($addOnIdentifier, $productIdentifier, $planIdentifier) {
        if (!$planIdentifier) {
            parent::__construct("You cannot add add-ons to the cart without a valid plan");
        }
        parent::__construct("The add-on $addOnIdentifier does not exist or is invalid for the product of type $productIdentifier and plan $planIdentifier");
    }

}
