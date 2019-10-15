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

    public function createSetupIntent() {
        return $this->stripeProvider->createSetupIntent();
    }

}
