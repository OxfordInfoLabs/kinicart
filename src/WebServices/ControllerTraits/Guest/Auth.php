<?php


namespace Kinicart\WebServices\ControllerTraits\Guest;


use Kinicart\Services\Security\AuthenticationService;

trait Auth {

    private $authenticationService;

    /**
     * @param \Kinicart\Services\Security\AuthenticationService $authenticationService
     */
    public function __construct($authenticationService) {
        $this->authenticationService = $authenticationService;
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
