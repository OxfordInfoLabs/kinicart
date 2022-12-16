<?php


namespace Kinicart\Objects\Payment;

/**
 * Payment base interface
 *
 * @implementation stripe \Kinicart\Objects\Payment\Stripe\StripePayment
 */
interface Payment {

    /**
     * Function used to charge the attached payment method for the amount specified
     *
     * @param float $amount
     * @param string $currency
     * @param mixed $paymentData
     *
     * @return mixed
     */
    public function charge($amount, $currency, $paymentData = null);

}
