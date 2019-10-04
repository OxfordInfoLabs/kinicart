<?php

namespace Kinicart\Exception\Product\PackagedProduct;

class NoSuchProductPlanException extends \Exception {

    /**
     * NoSuchProductPlanException constructor.
     */
    public function __construct($planIdentifier, $productIdentifier) {
        parent::__construct("The plan $planIdentifier does not exist for the product of type $productIdentifier");
    }
}
