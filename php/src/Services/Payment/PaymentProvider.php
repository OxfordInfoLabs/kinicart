<?php

namespace Kinicart\Services\Payment;

use Kinicart\Objects\Payment\PaymentMethod;


/**
 * Generic payment provider class.  Allows for expandable payment methods down the line.
 *
 * Interface PaymentProvider
 *
 * @implementation stripe \Kinicart\Services\Payment\Stripe\StripePaymentProvider
 *
 */
interface PaymentProvider {


    /**
     * @param array $data
     * @param bool $defaultMethod
     * @return PaymentMethod
     */
    public function savePaymentMethod($data = array(), $defaultMethod = false);

    /**
     * @param $id
     * @return mixed
     */
    public function removePaymentMethod($id);


}
