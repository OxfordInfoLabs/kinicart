<?php


namespace Kinicart\Services\Account;

use Kinicart\Objects\Account\Account;

/**
 * Fixed account provider - simply returns the constructed account
 *
 * Class FixedAccountProvider
 * @package Kinicart\Services\Account
 */
class FixedAccountProvider implements AccountProvider {

    /**
     * @var Account
     */
    private $account;

    /**
     * Create with a fixed account
     *
     * @param Account $account
     */
    public function __construct($account) {
        $this->account = $account;
    }

    /**
     * Provide the account - only required method
     *
     * @return Account
     */
    public function provideAccount() {
        return $this->account;
    }
}
