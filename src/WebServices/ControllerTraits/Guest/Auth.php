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

    /**
     * Check if email exists
     *
     * @http GET /emailExists
     *
     * @param $emailAddress
     * @return bool
     */
    public function emailExists($emailAddress) {
        return $this->authenticationService->emailExists($emailAddress);
    }

    /**
     * Validate the users password
     *
     * @http GET /validatePassword
     *
     * @param $emailAddress
     * @param $password
     * @param null $parentAccountId
     * @return bool
     */
    public function validateUserPassword($emailAddress, $password, $parentAccountId = null) {
        return $this->authenticationService->validateUserPassword($emailAddress, $password, $parentAccountId);
    }


}
