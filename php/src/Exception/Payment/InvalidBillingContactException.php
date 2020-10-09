<?php


namespace Kinicart\Exception\Payment;


class InvalidBillingContactException  extends \Exception {

    public function __construct() {
        parent::__construct("The billing contact supplied does not match a valid contact for the account");
    }

}
