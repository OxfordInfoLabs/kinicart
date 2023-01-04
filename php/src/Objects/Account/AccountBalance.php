<?php


namespace Kinicart\Objects\Account;


use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * Class AccountBalance
 *
 * @table kc_account_balance
 * @generate
 */
class AccountBalance extends ActiveRecord {

    /**
     * @var integer
     * @primaryKey
     */
    private $accountId;

    /**
     * @var float
     */
    private $balance = 0;


    /**
     * @var string
     */
    private $balanceCurrencyCode;


    /**
     * AccountBalance constructor.
     *
     * @param int $accountId
     * @param string $balanceCurrencyCode
     */
    public function __construct($accountId, $balanceCurrencyCode, $balance = 0) {
        $this->accountId = $accountId;
        $this->balanceCurrencyCode = $balanceCurrencyCode;
        $this->balance = $balance;
    }


    /**
     * @return int
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * @return float
     */
    public function getBalance() {
        return $this->balance;
    }

    /**
     * @return string
     */
    public function getBalanceCurrencyCode() {
        return $this->balanceCurrencyCode;
    }


}