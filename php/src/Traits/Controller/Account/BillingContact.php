<?php


namespace Kinicart\Traits\Controller\Account;


use Kinicart\Services\Account\AccountService;

trait BillingContact {

    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * BillingContact constructor.
     *
     * @param AccountService $accountService
     */
    public function __construct($accountService) {
        $this->accountService = $accountService;
    }


    /**
     * @http GET /
     *
     * @return \Kinicart\ValueObjects\Account\BillingContact
     */
    public function getBillingContact() {
        return $this->accountService->getBillingContact();
    }


    /**
     * @http POST /
     *
     * @param $billingContact
     */
    public function updateBillingContact($billingContact) {
        $this->accountService->updateBillingContact($billingContact);
    }

}