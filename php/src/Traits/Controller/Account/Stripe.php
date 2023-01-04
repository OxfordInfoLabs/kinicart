<?php


namespace Kinicart\Traits\Controller\Account;


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
     * Create a checkout session
     *
     * @http POST /checkoutSession
     *
     * @param mixed $payload
     * @return mixed
     */
    public function createCheckoutSession($payload = []) {
        $mode = $payload["mode"] ?? "payment";
        $cancelURL = $payload["cancelURL"] ?? "/cancel";
        $successURL = $payload["successURL"] ?? "/success";

        return $this->stripeService->createStripeCheckoutSession($mode, $cancelURL, $successURL);
    }

}
