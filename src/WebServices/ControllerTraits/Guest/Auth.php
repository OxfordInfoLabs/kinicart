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

    /**
     * Change the logged in users email
     *
     * @http GET /changeEmail
     *
     * @param $newEmailAddress
     * @param $password
     * @return \Kinicart\Objects\Security\User
     */
    public function changeUserEmail($newEmailAddress, $password) {
        return $this->authenticationService->changeUserEmail($newEmailAddress, $password);
    }

    /**
     * Change the logged in user backup email
     *
     * @http GET /changeBackupEmail
     *
     * @param $newEmailAddress
     * @param $password
     * @return \Kinicart\Objects\Security\User
     */
    public function changeUserBackupEmail($newEmailAddress, $password) {
        return $this->authenticationService->changeUserBackupEmail($newEmailAddress, $password);
    }

    /**
     * Change the logged in user mobile number
     *
     * @http GET /changeMobile
     *
     * @param $newMobile
     * @param $password
     * @return \Kinicart\Objects\Security\User
     */
    public function changeUserMobile($newMobile, $password) {
        return $this->authenticationService->changeUserMobile($newMobile, $password);
    }
}
