<?php


namespace Kinicart\Traits\Controller\Account;


use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Payment\PaymentMethod;
use Kinicart\Services\Payment\PaymentProvider;
use Kinicart\Services\Payment\PaymentService;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\DependencyInjection\InterfaceResolver;
use Kinikit\Core\DependencyInjection\MissingInterfaceImplementationException;
use Kinikit\Core\Logging\Logger;
use Kinikit\Persistence\Database\Connection\DatabaseConnection;

trait Payment {

    private $paymentService;


    /**
     * Payment constructor.
     * @param PaymentService $paymentService
     */
    public function __construct($paymentService) {
        $this->paymentService = $paymentService;
    }

    /**
     * Get the payment methods for the logged in account
     *
     * @http GET /methods
     */
    public function getPaymentMethods() {
        return $this->paymentService->getPaymentMethods();
    }

    /**
     * Unset the current default payment method and set the passed in method
     *
     * @http GET /default
     *
     * @param $methodId
     */
    public function setDefaultPaymentMethod($methodId) {
        $this->paymentService->setDefaultPaymentMethod($methodId);
    }

    /**
     * Save the payment method
     *
     * @http POST /saveMethod
     *
     * @param string[] $data
     * @param string $defaultMethod
     * @param string $provider
     * @return PaymentMethod
     * @throws MissingInterfaceImplementationException
     */
    public function addPaymentMethod($data = array(), $defaultMethod = null, $provider = null, $accountId = Account::LOGGED_IN_ACCOUNT) {
        /** @var PaymentProvider $paymentProvider */
        $paymentProvider = Container::instance()->getInterfaceImplementation(PaymentProvider::class, $provider);
        return $paymentProvider->savePaymentMethod($data, $defaultMethod);
    }

    /**
     * Removes the passed in method from the provider and database
     *
     * @http GET /remove
     *
     * @param $methodId
     * @param null $provider
     */
    public function removePaymentMethod($methodId, $provider = null) {
        /** @var PaymentProvider $paymentProvider */
        $paymentProvider = Container::instance()->getInterfaceImplementation(PaymentProvider::class, $provider);
        $paymentProvider->removePaymentMethod($methodId);
    }

}
