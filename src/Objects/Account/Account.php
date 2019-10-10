<?php


namespace Kinicart\Objects\Account;

/**
 * Extension of the core account class with cart functionality.
 *
 * @table ka_account
 * @noGenerate
 */
class Account extends \Kiniauth\Objects\Account\Account {

    /**
     * @oneToOne
     * @childJoinColumns account_id
     *
     * @var AccountData
     */
    private $accountData;

    /**
     * @return AccountData
     */
    public function getAccountData() {
        return $this->accountData ? $this->accountData : new AccountData();
    }

    /**
     * @param AccountData $accountData
     */
    public function setAccountData($accountData) {
        $this->accountData = $accountData;
    }


}
