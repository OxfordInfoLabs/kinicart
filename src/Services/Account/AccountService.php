<?php


namespace Kinicart\Services\Account;


use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\AccountSummary;

class AccountService {

    /**
     * Get an account summary, default to the logged in account.
     *
     * @param string $id
     * @return AccountSummary
     */
    public function getAccountSummary($id = Account::LOGGED_IN_ACCOUNT) {
        $accountSummary =  AccountSummary::fetch($id);
        return $accountSummary;
    }

}
