<?php


namespace Kinicart\Services\Payment;

use Kinicart\ValueObjects\Payment\PaymentResult;

/**
 * Payment base interface
 *
 * @implementation stripe \Kinicart\Objects\Payment\Stripe\StripePayment
 */
interface PaymentProvider {

    /**
     * Function used to charge the attached payment method for the amount specified.  This should
     * return payment specific data
     *
     * @param float $amount
     * @param string $currency
     * @param mixed $paymentData
     *
     * @return PaymentResult
     */
    public function charge($amount, $currency, $paymentData = null);

}
