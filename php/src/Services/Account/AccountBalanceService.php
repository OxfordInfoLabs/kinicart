<?php


namespace Kinicart\Services\Account;


use Kinicart\Exception\Account\InsufficientBalanceException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\AccountBalance;
use Kinicart\Services\Pricing\PricingService;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Persistence\Database\Connection\DatabaseConnection;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;

class AccountBalanceService {

    /**
     * @var PricingService
     */
    private $pricingService;

    /**
     * AccountBalanceService constructor.
     *
     * @param PricingService $pricingService
     */
    public function __construct($pricingService) {
        $this->pricingService = $pricingService;
    }


    /**
     * Ensure the account identified has enough balance to fulfil the payment amount in the
     * supplied currency
     *
     * @param float $paymentAmount
     * @param string $currencyCode
     * @param integer $accountId
     *
     * @return boolean
     */
    public function ensureBalance($paymentAmount, $currencyCode, $accountId = Account::LOGGED_IN_ACCOUNT) {

        $accountBalance = $this->getAccountBalance($accountId);

        // Convert the amount being ensured into account balance currency
        $convertedAmount = $this->pricingService->convertAmountToCurrency($paymentAmount, $currencyCode, $accountBalance->getBalanceCurrencyCode());

        // Return an indicator that we have enough balance
        return $convertedAmount <= $accountBalance->getBalance();
    }


    /**
     * Top up the balance for the supplied account id by the amount specified in the currency supplied.
     * If no currency supplied, assume the currency is account currency
     *
     * @param $topUpAmount
     * @param null $currencyCode
     * @param string $accountId
     */
    public function topUpBalance($topUpAmount, $currencyCode = null, $accountId = Account::LOGGED_IN_ACCOUNT) {

        $accountBalance = $this->getAccountBalance($accountId);

        // Convert the top up amount if currency code supplied
        if ($currencyCode) {
            $topUpAmount = $this->pricingService->convertAmountToCurrency($topUpAmount, $currencyCode, $accountBalance->getBalanceCurrencyCode());
        }

        /**
         * @var DatabaseConnection $databaseConnection
         */
        $databaseConnection = Container::instance()->get(DatabaseConnection::class);
        $databaseConnection->execute("UPDATE kc_account_balance SET balance = ROUND(balance + ?, 2) WHERE account_id = ?", $topUpAmount, $accountId);
    }


    /**
     * Deduct the amount specified from the supplied account in the passed currency
     *
     * @param float $deductionAmount
     * @param string $currencyCode
     * @param integer $accountId
     */
    public function deductFromBalance($deductionAmount, $currencyCode, $accountId = Account::LOGGED_IN_ACCOUNT) {

        $accountBalance = $this->getAccountBalance($accountId);

        // Convert the amount being ensured into account balance currency
        $convertedAmount = $this->pricingService->convertAmountToCurrency($deductionAmount, $currencyCode, $accountBalance->getBalanceCurrencyCode());

        // If insufficient balance throw an exception
        if ($convertedAmount > $accountBalance->getBalance())
            throw new InsufficientBalanceException();

        /**
         * @var DatabaseConnection $databaseConnection
         */
        $databaseConnection = Container::instance()->get(DatabaseConnection::class);
        $databaseConnection->execute("UPDATE kc_account_balance SET balance = ROUND(balance - ?, 2) WHERE account_id = ?", $convertedAmount, $accountId);

    }


    /**
     * Get the account balance for a given account
     *
     * @param $accountId
     * @return AccountBalance
     */
    private function getAccountBalance($accountId) {
        try {
            /**
             * @var AccountBalance $balance
             */
            $balance = AccountBalance::fetch($accountId);
        } catch (ObjectNotFoundException $e) {

            /**
             * @var Account $account
             */
            $account = Account::fetch($accountId);
            $accountCurrencyCode = $account->getAccountData()->getCurrencyCode();

            // Create new balance object
            $balance = new AccountBalance($accountId, $accountCurrencyCode);
            $balance->save();
        }

        return $balance;

    }

}