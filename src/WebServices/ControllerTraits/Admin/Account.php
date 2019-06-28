<?php

namespace Kinicart\WebServices\ControllerTraits\Admin;

use Kinicart\Objects\Account\AccountSummary;
use Kinicart\Services\Account\AccountService;

trait Account {


    private $accountService;

    /**
     * Account constructor.
     * @param \Kinicart\Services\Account\AccountService $accountService
     */
    public function __construct($accountService) {
        $this->accountService = $accountService;
    }

    /**
     * Get an account defaulting to logged in account
     *
     * @http GET /
     *
     * @return AccountSummary
     *
     */
    public function getAccount($accountId = \Kinicart\Objects\Account\Account::LOGGED_IN_ACCOUNT) {
       return AccountSummary::fetch($accountId);
    }
}
