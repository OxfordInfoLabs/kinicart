<?php


namespace Kinicart\Exception\Payment;


class MissingPaymentMethodException extends \Exception {

    public function __construct() {
        parent::__construct("No payment method has been supplied for an order with a non zero total");
    }

}
