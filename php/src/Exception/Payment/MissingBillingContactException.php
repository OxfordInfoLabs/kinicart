<?php


namespace Kinicart\Exception\Payment;


class MissingBillingContactException extends \Exception {

    public function __construct() {
        parent::__construct("No billing contact has been supplied for an order with a non zero total");
    }

}
