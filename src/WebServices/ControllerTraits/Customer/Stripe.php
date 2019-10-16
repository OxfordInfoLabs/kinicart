<?php


namespace Kinicart\WebServices\ControllerTraits\Customer;


use Kinicart\Services\Payment\Stripe\StripeService;

trait Stripe {

    private $stripeService;

    /**
     * Stripe constructor.
     * @param StripeService $stripeService
     */
    public function __construct($stripeService) {
        $this->stripeService = $stripeService;
    }

    /**
     * Return the publishable key from settings
     *
     * @http GET /publishableKey
     *
     * @return string|null
     */
    public function getPublishableKey() {
        return $this->stripeService->getPublishableKey();
    }

    /**
     * @http GET /createSetupIntent
     *
     * @param $returnURL string
     * @param $customer string
     *
     * @return array
     */
    public function createSetupIntent($returnURL, $customer = null) {
        return $this->stripeService->createSetupIntent($returnURL, $customer);
    }

}
