<?php


namespace Kinicart\WebServices\ControllerTraits\API;


use Kinicart\Objects\Account\AccountSummary;

trait Account {

    private $securityService;

    /**
     * Construct with a security service.
     *
     * Account constructor.
     *
     * @param \Kinicart\Services\Security\SecurityService $securityService
     */
    public function __construct($securityService) {
        $this->securityService = $securityService;
    }


    /**
     * @http GET /
     *
     * @return \Kinicart\Objects\Account\AccountSummary
     */
    public function getAccount() {
        list($user, $account) = $this->securityService->getLoggedInUserAndAccount();
        return $account->generateSummary();
    }

}
