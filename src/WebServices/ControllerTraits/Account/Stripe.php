<?php


namespace Kinicart\WebServices\ControllerTraits\Account;


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
     * @http GET /createSetupIntent
     */
    public function createSetupIntent() {
        return $this->stripeService->createSetupIntent();
    }

}
