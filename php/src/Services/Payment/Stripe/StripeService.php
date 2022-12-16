<?php


namespace Kinicart\Services\Payment\Stripe;


use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Logging\Logger;
use Kinikit\MVC\Session\PHPSession;
use Stripe\Exception\ApiErrorException;

class StripeService {

    /** @var string Used for capturing payment immediately */
    const CHECKOUT_MODE_PAYMENT = 'payment';
    /** @var string Save payment details to charge your customers later. */
    const CHECKOUT_MODE_SETUP = 'setup';


    public function createStripeCheckoutSession($lineItems = [], $mode = self::CHECKOUT_MODE_SETUP, $cancelURL = '/cancel', $successURL = '/success', $currency = 'gbp') {
        /** @var PHPSession $session */
        $session = Container::instance()->get(PHPSession::class);

        $checkoutSession = \Stripe\Checkout\Session::create([
            "success_url" => $successURL,
            "cancel_url" => $cancelURL,
            "mode" => $mode,
            "line_items" => $lineItems,
            "currency" => $currency,
            "metadata" => [
                "session" => $session->getId()
            ]
        ]);

        return $checkoutSession->toArray()["url"];
    }

    public function checkoutSessionCompleted($payload) {
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = 'whsec_5b8367facabd78a744996e193acc70372c6bcce2aabc4e275ef115ea2027ba65';
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            Logger::log($e);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            Logger::log($e);
        }
        Logger::log($event);
        // Handle the event
        if ($event->type === 'checkout.session.completed') {
            $paymentIntent = $event->data->object;
            Logger::log($paymentIntent);
            return $paymentIntent;
        }
    }

    /**
     * @throws ApiErrorException
     */
    public function capturePaymentIntent($paymentIntent) {
        \Stripe\PaymentIntent::retrieve($paymentIntent)->capture();
    }

}
