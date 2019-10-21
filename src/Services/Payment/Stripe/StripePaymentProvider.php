<?php

namespace Kinicart\Services\Payment\Stripe;

use Exception;
use Kiniauth\Objects\Application\Setting;
use Kiniauth\Services\Security\SecurityService;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Payment\PaymentMethod;
use Kinicart\Objects\Payment\Stripe\StripeCustomer;
use Kinicart\Services\Payment\PaymentProvider;
use Kinikit\Core\Logging\Logger;
use Kinikit\Persistence\Database\Connection\DatabaseConnection;
use Stripe\Customer;

class StripePaymentProvider implements PaymentProvider {

    private $securityService;
    /**
     * @var DatabaseConnection
     */
    private $databaseConnection;

    /**
     * StripePaymentProvider constructor.
     * @param SecurityService $securityService
     * @param DatabaseConnection $databaseConnection
     */
    public function __construct($securityService, $databaseConnection) {
        $this->securityService = $securityService;
        $this->databaseConnection = $databaseConnection;

        $secretKey = $this->getSecretKey();
        \Stripe\Stripe::setApiKey($secretKey);
    }

    public function createSetupIntent($confirm = false, $returnURL = null, $customer = null) {
        $setup_intent = \Stripe\SetupIntent::create([
            'usage' => 'off_session',
            'confirm' => $confirm,
            'return_url' => $returnURL,
            'customer' => $customer
        ]);
        return $setup_intent->toArray();
    }

    /**
     * @param array $data
     * @param bool $defaultMethod
     * @return PaymentMethod
     */
    public function savePaymentMethod($data = array(), $defaultMethod = false, $accountId = Account::LOGGED_IN_ACCOUNT) {
        if (isset($data["paymentMethod"])) {
            /** @var StripeCustomer $customer */
            $customer = $this->getStripeCustomer(true, $accountId);

            $payment_method = \Stripe\PaymentMethod::retrieve($data["paymentMethod"]);
            $payment_method->attach(['customer' => $customer->getCustomerId()]);

            if ($defaultMethod) {
                $this->databaseConnection->execute("UPDATE kc_payment_method SET default_method = 0 WHERE account_id = ? AND default_method = 1", $accountId);
            }

            $paymentMethod = new PaymentMethod(
                $customer->getAccountId(),
                PaymentMethod::STRIPE_PAYMENT_METHOD,
                $payment_method->toArray(),
                $defaultMethod
            );

            $paymentMethod->save();
        } else {
            throw new Exception("Please supply a payment method string created from the client");
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function removePaymentMethod($id, $accountId = Account::LOGGED_IN_ACCOUNT) {
        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = PaymentMethod::fetch($id);
        $wasDefault = $paymentMethod->isDefaultMethod();

        $payment_method = \Stripe\PaymentMethod::retrieve($paymentMethod->getData()["id"]);
        $payment_method->detach();

        $paymentMethod->remove();

        if ($wasDefault) {
            $methods = PaymentMethod::filter("WHERE account_id = ?", $accountId);
            if (sizeof($methods) > 0) {
                $methods[0]->setDefaultMethod(true);
                $methods[0]->save();
            }
        }
    }

    /**
     * Return the publishable stripe key from settings
     *
     * @return string|null
     */
    public function getPublishableKey() {
        $parentAccountId = $this->securityService->getParentAccountId();
        $settings = Setting::filter("WHERE (parent_account_id = ? OR parent_account_id = 0) AND setting_key = 'publishableStripeKey'",
            $parentAccountId);
        if (sizeof($settings) === 1) {
            return $settings[0]->getValue();
        } else if (sizeof($settings) > 1) {
            return array_filter($settings, function ($setting) use ($parentAccountId) {
                return $setting->getParentAccountId() == $parentAccountId;
            })[0]->getValue();
        }
        return null;
    }

    /**
     * Return the stripe customer object, or return a new one.
     *
     * @param bool $createNew
     * @param string $accountId
     *
     * @return StripeCustomer|mixed|null
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function getStripeCustomer($createNew = false, $accountId = Account::LOGGED_IN_ACCOUNT) {
        try {
            return StripeCustomer::fetch($accountId);
        } catch (Exception $e) {
            if ($createNew) {
                /** @var Account $account */
                $account = Account::fetch($accountId);
                $customer = Customer::create([
                    'name' => $account->getName()
                ]);
                $stripeCustomer = new StripeCustomer($accountId, $customer->id);
                $stripeCustomer->save();
                return $stripeCustomer;
            }
            return null;
        }
    }

    /**
     * Return the secret key from the settings table
     *
     * @return string|null
     */
    private function getSecretKey() {
        $parentAccountId = $this->securityService->getParentAccountId();
        $settings = Setting::filter("WHERE (parent_account_id = ? OR parent_account_id = 0) AND setting_key = ?",
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
}
