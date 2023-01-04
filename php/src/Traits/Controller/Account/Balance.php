<?php


namespace Kinicart\Traits\Controller\Account;


use Kinicart\Objects\Account\AccountBalance;
use Kinicart\Services\Account\AccountBalanceService;

trait Balance {

    /**
     * @var AccountBalanceService
     */
    private $accountBalanceService;

    /**
     * Balance constructor.
     *
     * @param AccountBalanceService $accountBalanceService
     */
    public function __construct(AccountBalanceService $accountBalanceService) {
        $this->accountBalanceService = $accountBalanceService;
    }


    /**
     * @http GET /
     *
     * @return AccountBalance
     */
    public function getBalance() {
        return $this->accountBalanceService->getAccountBalance();
    }

}