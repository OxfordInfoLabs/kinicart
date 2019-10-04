<?php

namespace Kinicart\Exception\Product\PackagedProduct;

use Kinikit\Core\Exception\ItemNotFoundException;

class NoSuchProductPlanException extends ItemNotFoundException {

    /**
     * NoSuchProductPlanException constructor.
     */
    public function __construct($planIdentifier, $productIdentifier) {
        parent::__construct("The plan $planIdentifier does not exist for the product of type $productIdentifier");
    }
}
