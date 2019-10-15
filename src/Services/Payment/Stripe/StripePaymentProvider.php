<?php

namespace Kinicart\Services\Payment\Stripe;

use Kiniauth\Objects\Application\Setting;
use Kiniauth\Services\Security\SecurityService;
use Kinicart\Objects\Payment\PaymentMethod;
use Kinicart\Services\Payment\PaymentProvider;
use Kinikit\Core\Logging\Logger;
use Stripe\Stripe;

class StripePaymentProvider implements PaymentProvider {

    private $securityService;

    /**
     * StripePaymentProvider constructor.
     * @param SecurityService $securityService
     */
    public function __construct($securityService) {
        $this->securityService = $securityService;
        $secretKey = $this->getSecretKey();
        Stripe::setApiKey($secretKey);
//        \Stripe\Stripe::setApiKey($secretKey);
    }

    public function createSetupIntent() {
        $setup_intent = \Stripe\SetupIntent::create([
            'usage' => 'off_session', // The default usage is off_session
        ]);
        Logger::log($setup_intent);
    }

    /**
     * @param array $data
     * @param bool $defaultMethod
     * @return PaymentMethod
     */
    public function savePaymentMethod($data = array(), $defaultMethod = false) {
        // TODO: Implement savePaymentMethod() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function removePaymentMethod($id) {
        // TODO: Implement removePaymentMethod() method.
    }

    /**
     * Return the secret key from the settings table
     *
     * @return string|null
     */
    private function getSecretKey() {
        $parentAccountId = $this->securityService->getParentAccountId() ?? null;
        $setting = Setting::filter("WHERE parent_account_id = ? AND setting_key = ?",
            $parentAccountId, 'secretStripeKey');
        if (sizeof($setting) > 0) {
            return $setting[0]["value"];
        }
        return null;
    }
}
