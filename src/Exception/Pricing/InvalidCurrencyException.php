<?php


namespace Kinicart\Exception\Pricing;


use Kinikit\Core\Exception\ItemNotFoundException;
use Throwable;

class InvalidCurrencyException extends ItemNotFoundException {

    public function __construct($currencyCode) {
        parent::__construct("The currency with code $currencyCode does not exist");
    }

}
