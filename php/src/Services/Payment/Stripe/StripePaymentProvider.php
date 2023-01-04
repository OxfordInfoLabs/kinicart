<?php


namespace Kinicart\Services\Payment\Stripe;

use Kinicart\Services\Payment\PaymentProvider;
use Kinicart\ValueObjects\Payment\PaymentResult;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Logging\Logger;

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
    public function charge($amount, $currency, $paymentData = null) {
        Logger::log($paymentData);
        if (isset($paymentData["paymentIntent"])) {
            /** @var StripeService $stripeService */
            $stripeService = Container::instance()->get(StripeService::class);
            $stripeService->capturePaymentIntent($paymentData["paymentIntent"]);
            return new PaymentResult(PaymentResult::STATUS_SUCCESS, $paymentData["paymentIntent"]);
        }

        return null;
    }

}
