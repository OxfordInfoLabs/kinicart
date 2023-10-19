<?php


namespace Kinicart\Objects\Account;

use Kiniauth\Objects\Security\AccountRole;

/**
 * Extension of the core account class with cart functionality.
 *
 * @table ka_account
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
        if (!$this->accountData) {
            $this->accountData = new AccountData();
        }
        return $this->accountData;
    }

    /**
     * @param AccountData $accountData
     */
    public function setAccountData($accountData) {
        $this->accountData = $accountData;
    }

    /**
     * Return account roles and fall back to tier roles if none defined
     *
     * @return string[]
     */
    public function returnAccountPrivileges() {
        $accountPrivileges = parent::returnAccountPrivileges();
        if (!$accountPrivileges && $this->getAccountData()->getTierPrivileges()) {
            $accountPrivileges = $this->getAccountData()->getTierPrivileges() ?? [];
        }
        return $accountPrivileges;
    }


}
