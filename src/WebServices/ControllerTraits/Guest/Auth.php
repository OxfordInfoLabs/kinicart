<?php


namespace Kinicart\WebServices\ControllerTraits\Guest;


use Kinicart\Services\Security\AuthenticationService;

trait Auth {

    private $authenticationService;
    private $securityService;

    /**
     * @param \Kinicart\Services\Security\AuthenticationService $authenticationService
     * @param \Kinicart\Services\Security\SecurityService $securityService
     */
    public function __construct($authenticationService, $securityService) {
        $this->authenticationService = $authenticationService;
        $this->securityService = $securityService;
    }

    /**
     * Return the logged in user/account
     *
     * @http GET /
     */
    public function getLoggedInUserAccount() {
        return $this->securityService->getLoggedInUserAndAccount();
    }

    /**
     * Log in with an email address and password.
     *
     * @http GET /login
     *
     * @param $emailAddress
     * @param $password
     */
    public function logIn($emailAddress, $password) {
        return $this->authenticationService->login($emailAddress, $password);
    }

    /**
     * Logout
     *
     * @http GET /logout
     */
    public function logOut() {
        $this->authenticationService->logout();
    }
}
