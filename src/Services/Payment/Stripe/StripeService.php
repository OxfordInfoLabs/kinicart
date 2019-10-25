<?php


namespace Kinicart\Services\Payment\Stripe;


class StripeService {

    private $stripeProvider;

    /**
     * StripeService constructor.
     * @param StripePaymentProvider $stripeProvider
     */
    public function __construct($stripeProvider) {
        $this->stripeProvider = $stripeProvider;
    }

    public function getPublishableKey() {
        return $this->stripeProvider->getPublishableKey();
    }

    public function createSetupIntent($returnURL = null, $customer = null) {
        return $this->stripeProvider->createSetupIntent($returnURL, $customer);
    }

    public function createPaymentIntent($amount, $currency = "gbp", $customer = null, $paymentMethod = null) {
        return $this->stripeProvider->createPaymentIntent($amount, $currency, $customer, $paymentMethod);
    }

}
