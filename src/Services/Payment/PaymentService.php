<?php


namespace Kinicart\Services\Payment;


use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Payment\PaymentMethod;
use Kinikit\Persistence\Database\Connection\DatabaseConnection;

class PaymentService {

    /**
     * @var DatabaseConnection
     */
    private $databaseConnection;

    /**
     * PaymentService constructor.
     *
     * @param DatabaseConnection $databaseConnection
     */
    public function __construct($databaseConnection) {
        $this->databaseConnection = $databaseConnection;
    }

    public function getPaymentMethods($accountId = Account::LOGGED_IN_ACCOUNT) {
        return PaymentMethod::filter("WHERE account_id = ?", $accountId);
    }

    public function setDefaultPaymentMethod($methodId, $accountId = Account::LOGGED_IN_ACCOUNT) {
        $this->databaseConnection->execute("UPDATE kc_payment_method SET default_method = 0 WHERE account_id = ? AND default_method = 1", $accountId);
        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = PaymentMethod::fetch($methodId);
        $paymentMethod->setDefaultMethod(true);
        $paymentMethod->save();
    }
}
