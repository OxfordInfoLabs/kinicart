<?php


namespace Kinicart\Objects\Payment;


interface Payment {

    /**
     * Function used to charge the attached payment method for the amount specified
     *
     * @param $amount
     * @param $currency
     * @return mixed
     */
    public function charge($amount, $currency, $paymentData);

}
