<?php


namespace Kinicart\Exception\Pricing;


use Kinikit\Core\Exception\ItemNotFoundException;
use Throwable;

class InvalidTierException extends ItemNotFoundException {

    public function __construct($tierId) {
        parent::__construct("The tier with id $tierId does not exist");
    }

}
