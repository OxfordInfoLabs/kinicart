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
    
    public function createSetupIntent($returnURL, $customer = null) {
        return $this->stripeProvider->createSetupIntent($returnURL, $customer);
    }

}
