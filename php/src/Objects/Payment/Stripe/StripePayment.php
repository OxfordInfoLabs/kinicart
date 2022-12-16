<?php


namespace Kinicart\Objects\Payment\Stripe;

use Kinicart\Objects\Payment\Payment;
use Kinicart\Services\Payment\Stripe\StripeService;
use Kinikit\Core\DependencyInjection\Container;

/**
 * Definition of the stripe data stored against a payment method
 *
 * Class StripePayment
 * @package Kinicart\Objects\Payment\Stripe
 *
 */
class StripePayment implements Payment {

    /**
     * @param $amount
     * @param $currency
     * @param $paymentData mixed
     * @return mixed|void
     */
    public function charge($amount, $currency, $paymentData = []) {
        if (isset($paymentData["paymentIntent"])) {
            /** @var StripeService $stripeService */
            $stripeService = Container::instance()->get(StripeService::class);
            $stripeService->capturePaymentIntent($paymentData["paymentIntent"]);
        }
    }

}
