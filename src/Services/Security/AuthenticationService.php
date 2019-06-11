<?php


namespace Kinicart\Services\Security;


use Kinicart\Exception\Security\InvalidAPICredentialsException;
use Kinicart\Exception\Security\InvalidLoginException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Security\User;


/**
 * AuthenticationService object for coordinating authentication functions for Kinicart.
 *
 * Class AuthenticationService
 * @package Kinicart\Workers\Application
 */
class AuthenticationService {

    private $settingsService;
    private $session;
    private $securityService;

    /**
     * @param \Kinicart\Services\Application\SettingsService $settingsService
     * @param \Kinicart\Services\Application\Session $session
     * @param \Kinicart\Services\Security\SecurityService $securityService
     */
    public function __construct($settingsService, $session, $securityService) {
        $this->settingsService = $settingsService;
        $this->session = $session;
        $this->securityService = $securityService;
    }

    /**
     * Boolean indicator as to whether or not an email address exists.
     *
     * @param $emailAddress
     * @param null $contextKey
     */
    public function emailExists($emailAddress, $parentAccountId = null) {

        if ($parentAccountId === null) {
            $parentAccountId = $this->session->__getActiveParentAccountId() ? $this->session->__getActiveParentAccountId() : 0;
        }

        return User::countQuery("WHERE emailAddress = ? AND parentAccountId = ?", $emailAddress, $parentAccountId) > 0;
    }


    /**
     * Log in with an email address and password.
     *
     * @param $emailAddress
     * @param $password
     *
     * @objectInterceptorDisabled
     */
    public function login($emailAddress, $password, $parentAccountId = null) {

        if ($parentAccountId === null) {
            $parentAccountId = $this->session->__getActiveParentAccountId() ? $this->session->__getActiveParentAccountId() : 0;
        }

        $matchingUsers = User::query("WHERE emailAddress = ? AND hashedPassword = ? AND parentAccountId = ?", $emailAddress, hash("md5", $password), $parentAccountId);


        // If there is a matching user, return it now.
        if (sizeof($matchingUsers) > 0) {
            $this->securityService->logIn($matchingUsers[0]);
            return 1;

        } else {
            throw new InvalidLoginException();
        }


    }


    /**
     * Authenticate an account by key and secret
     *
     * @param $apiKey
     * @param $apiSecret
     *
     * @objectInterceptorDisabled
     */
    public function apiAuthenticate($apiKey, $apiSecret) {

        $matchingAccounts = Account::query("WHERE apiKey = ? AND apiSecret = ?", $apiKey, $apiSecret);

        // If there is a matching user, return it now.
        if (sizeof($matchingAccounts) > 0) {
            $this->securityService->login(null, $matchingAccounts[0]);
        } else {
            throw new InvalidAPICredentialsException();
        }


    }


    /**
     * Update the active parent URL according to a referring URL.
     *
     * @param $referringURL
     */
    public function updateActiveParentAccount($referringURL) {

        // Check the referring URL to see whether or not we need to update our logged in state.
        $splitReferrer = explode("//", $referringURL);

        $referer = sizeof($splitReferrer) > 1 ? explode("/", $splitReferrer[1])[0] : $splitReferrer[0];

        // If the referer differs from the session value, check some stuff.
        if ($referer !== $this->session->__getReferringURL()) {
            $this->session->__setReferringURL($referer);

            // Now attempt to look up the setting by key and value
            $setting = $this->settingsService->getSettingByKeyAndValue("referringDomains", $referer);
            if ($setting) {
                $parentAccountId = $setting->getParentAccountId();
            } else {
                $parentAccountId = 0;
            }

            // Make sure we log out if the active parent account id has changed.
            if ($this->session->__getActiveParentAccountId() != $parentAccountId) {
                $this->logOut();
            }

            $this->session->__setActiveParentAccountId($parentAccountId);


        }

    }


    /**
     * Log out function.
     */
    public function logout() {
        $this->securityService->logOut();
    }


}
