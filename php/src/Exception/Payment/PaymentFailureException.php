<?php


namespace Kinicart\Exception\Payment;


use Kinicart\ValueObjects\Payment\PaymentResult;


class PaymentFailureException extends \Exception {

    /**
     * Construct with Payment Result
     *
     * PaymentFailureException constructor.
     * @param PaymentResult $paymentResult
     */
    public function __construct($paymentResult) {
        parent::__construct("There was an error processing your payment: " . $paymentResult->getErrorMessage());
    }


}