<?php

namespace Kinicart\Services\Account;

use Kinicart\Objects\Account\Account;

/**
 * Provide an account - used by the cart where the account can change as things get logged in etc.
 *
 *
 * @package Kinicart\Objects\Account
 */
interface AccountProvider {

    /**
     * Provide the account - only required method
     *
     * @return Account
     */
    public function provideAccount();
}
