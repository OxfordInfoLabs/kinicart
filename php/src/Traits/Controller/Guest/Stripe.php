<?php


namespace Kinicart\Traits\Controller\Guest;


use Kinicart\Services\Payment\Stripe\StripeService;
use Kinikit\Core\Logging\Logger;

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
     * @http POST /checkoutCompleted
     *
     * @param mixed $payload
     * @return mixed
     */
    public function checkoutSessionCompleted($payload) {
        return $this->stripeService->checkoutSessionCompleted($payload);
    }

}
