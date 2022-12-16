<?php


namespace Kinicart\Objects\Payment\Stripe;

use Kinicart\Objects\Payment\PaymentProvider;
use Kinicart\Services\Payment\Stripe\StripeService;
use Kinicart\ValueObjects\Payment\PaymentResult;
use Kinikit\Core\DependencyInjection\Container;

/**
 * Definition of the stripe data stored against a payment method
 *
 * Class StripePayment
 * @package Kinicart\Objects\Payment\Stripe
 *
 */
class StripePaymentProvider implements PaymentProvider {

    /**
     * @param $amount
     * @param $currency
     * @param $paymentData mixed
     *
     * @return PaymentResult
     */
    public function charge($amount, $currency, $paymentData = []) {
        if (isset($paymentData["paymentIntent"])) {
            /** @var StripeService $stripeService */
            $stripeService = Container::instance()->get(StripeService::class);
            $stripeService->capturePaymentIntent($paymentData["paymentIntent"]);
        }
    }

}
