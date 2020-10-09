<?php


namespace Kinicart\Exception\Payment;


class InvalidPaymentMethodException extends \Exception {

    public function __construct() {
        parent::__construct("The payment method supplied does not match a valid payment method for the account");
    }

}
