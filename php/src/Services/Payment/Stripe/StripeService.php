<?php


namespace Kinicart\Services\Payment\Stripe;


use Kiniauth\Objects\Application\Setting;
use Kiniauth\Services\Application\Session;
use Kiniauth\Services\Security\SecurityService;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Cart\CartItem;
use Kinicart\Services\Cart\SessionCart;
use Kinicart\Services\Order\OrderService;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Logging\Logger;
use Kinikit\MVC\Session\PHPSession;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class StripeService {

    /**
     * @var SecurityService $securityService
     */
    private $securityService;

    /**
     * @var $session Session
     */
    private $session;

    private $orderService;

    private $sessionCart;


    const CHECKOUT_MODE_PAYMENT = 'payment';

    /**
     * StripePaymentProvider constructor.
     * @param SecurityService $securityService
     * @param Session $session
     * @param OrderService $orderService
     * @param SessionCart $sessionCart
     */
    public function __construct($securityService, $session, $orderService, $sessionCart) {
        $this->securityService = $securityService;
        $this->session = $session;
        $this->orderService = $orderService;
        $this->sessionCart = $sessionCart;

        $secretKey = $this->getSecretKey();
        Stripe::setApiKey($secretKey);
    }

    public function createStripeCheckoutSession($mode = self::CHECKOUT_MODE_PAYMENT, $cancelURL = '/cancel', $successURL = '/success') {
        $lineItems = [];
        $sessionCart = $this->sessionCart->get();

        /** @var Account $account */
        $account = $sessionCart->getAccountProvider()->provideAccount();

        $currency = $account->getAccountData()->getCurrencyCode();

        /** @var CartItem $item */
        foreach ($sessionCart->getItems() as $item) {
            $lineItems[] = [
                "price_data" => [
                    "currency" => $currency,
                    "unit_amount" => intval(strval($item->getUnitPrice($currency) * 100)),
                    "product_data" => [
                        "name" => $item->getTitle()
                    ]
                ],
                "quantity" => $item->getQuantity()
            ];
        }

        if (intval(strval($sessionCart->getTaxes() * 100)) > 0) {
            $lineItems[] = [
                "price_data" => [
                    "currency" => $currency,
                    "unit_amount" => intval(strval($sessionCart->getTaxes() * 100)),
                    "product_data" => [
                        "name" => "Applicable Taxes"
                    ]
                ],
                "quantity" => 1
            ];
        }

        $checkoutData = [
            "success_url" => $successURL,
            "cancel_url" => $cancelURL,
            "mode" => $mode,
            "line_items" => $lineItems,
            "currency" => $currency,
            "metadata" => [
                "session" => $this->session->getId()
            ],
            "payment_intent_data" => [
                'capture_method' => 'manual',
            ],
        ];

        $checkoutSession = \Stripe\Checkout\Session::create($checkoutData);

        return $checkoutSession->toArray()["url"];
    }

    public function checkoutSessionCompleted($payload) {
        $rawPayload = @file_get_contents('php://input');
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = $this->getEndpointSecret();
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        try {
            $event = \Stripe\Webhook::constructEvent(
                $rawPayload, $sig_header, $endpoint_secret
            );

            // Handle the event
            if ($event->type === 'checkout.session.completed') {
                $paymentIntent = $event->data->object;
                $session = $paymentIntent->metadata["session"];
                $paymentIntentId = $paymentIntent->payment_intent;

                $this->session->join($session);

                $this->orderService->processOrder('stripe', ["paymentIntent" => $paymentIntentId]);

                return $paymentIntent;
            }
        } catch (\UnexpectedValueException $e) {
            Logger::log($e);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Logger::log($e);
        }
    }

    /**
     * @throws ApiErrorException
     */
    public function capturePaymentIntent($paymentIntent) {
        \Stripe\PaymentIntent::retrieve($paymentIntent)->capture();
    }

    /**
     * Return the secret key from the settings table
     *
     * @return string|null
     */
    private function getSecretKey() {
        $parentAccountId = $this->securityService->getParentAccountId();
        $settings = Setting::filter("WHERE (parent_account_id = ? OR parent_account_id = 0 OR parent_account_id IS NULL) AND setting_key = ?",
            $parentAccountId, 'secretStripeKey');
        if (sizeof($settings) === 1) {
            return $settings[0]->getValue();
        } else if (sizeof($settings) > 1) {
            return array_filter($settings, function ($setting) use ($parentAccountId) {
                return $setting->getParentAccountId() == $parentAccountId;
            })[0]->getValue();
        }
        return null;
    }

    private function getEndpointSecret() {
        $parentAccountId = $this->securityService->getParentAccountId();
        $settings = Setting::filter("WHERE (parent_account_id = ? OR parent_account_id = 0 OR parent_account_id IS NULL) AND setting_key = ?",
            $parentAccountId, 'stripeEndpointSecret');

        if (sizeof($settings) === 1) {
            return $settings[0]->getValue();
        }

        return null;
    }

}
