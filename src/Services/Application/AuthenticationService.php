<?php


namespace Kinicart\Services\Application;


use Kinicart\Exception\Application\AccountSuspendedException;
use Kinicart\Exception\Application\InvalidAPICredentialsException;
use Kinicart\Exception\Application\InvalidLoginException;
use Kinicart\Exception\Application\UserSuspendedException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\User;
use Kinicart\Objects\Application\Session;

/**
 * AuthenticationService object for coordinating authentication functions for Kinicart.
 *
 * Class AuthenticationService
 * @package Kinicart\Workers\Application
 */
class AuthenticationService {

    private $settingsService;

    /**
     * @param \Kinicart\Services\Application\SettingsService $settingsService
     */
    public function __construct($settingsService) {
        $this->settingsService = $settingsService;
    }

    /**
     * Boolean indicator as to whether or not an email address exists.
     *
     * @param $emailAddress
     * @param null $contextKey
     */
    public function emailExists($emailAddress, $parentAccountId = null) {

        if ($parentAccountId === null) {
            $parentAccountId = Session::instance()->getActiveParentAccountId() ? Session::instance()->getActiveParentAccountId() : 0;
        }

        return User::countQuery("WHERE emailAddress = ? AND parentAccountId = ?", $emailAddress, $parentAccountId) > 0;
    }


    /**
     * Log in with an email address and password.
     *
     * @param $emailAddress
     * @param $password
     */
    public function logIn($emailAddress, $password, $parentAccountId = null) {

        if ($parentAccountId === null) {
            $parentAccountId = Session::instance()->getActiveParentAccountId() ? Session::instance()->getActiveParentAccountId() : 0;
        }

        $matchingUsers = User::query("WHERE emailAddress = ? AND hashedPassword = ? AND parentAccountId = ?", $emailAddress, hash("md5", $password), $parentAccountId);

        // If there is a matching user, return it now.
        if (sizeof($matchingUsers) > 0) {
            $this->setLoggedInDetails($matchingUsers[0]);
        } else {
            throw new InvalidLoginException();
        }


    }


    /**
     * Authenticate an account by key and secret
     *
     * @param $apiKey
     * @param $apiSecret
     */
    public function apiAuthenticate($apiKey, $apiSecret) {

        $matchingAccounts = Account::query("WHERE apiKey = ? AND apiSecret = ?", $apiKey, $apiSecret);

        // If there is a matching user, return it now.
        if (sizeof($matchingAccounts) > 0) {
            $this->setLoggedInDetails(null, $matchingAccounts[0]);
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
        if ($referer !== Session::instance()->getReferringURL()) {
            Session::instance()->setReferringURL($referer);

            // Now attempt to look up the setting by key and value
            $setting = $this->settingsService->getSettingByKeyAndValue("referringDomains", $referer);
            if ($setting) {
                $parentAccountId = $setting->getAccountId();
            } else {
                $parentAccountId = 0;
            }

            // Make sure we log out if the active parent account id has changed.
            if (Session::instance()->getActiveParentAccountId() != $parentAccountId) {
                $this->logOut();
            }

            Session::instance()->setActiveParentAccountId($parentAccountId);


        }

    }


    /**
     * Log out function.
     */
    public function logOut() {
        Session::instance()->logOut();
    }


    // Set logged in details (either a user or an account if an API login).
    private function setLoggedInDetails($user = null, $account = null) {

        // Log out just in case we are already logged in to clean up.
        $this->logOut();

        if ($user) {


            // Throw suspended exception if user is suspended.
            if ($user->getStatus() == User::STATUS_SUSPENDED) {
                throw new UserSuspendedException();
            }

            // Throw invalid login if still pending.
            if ($user->getStatus() == User::STATUS_PENDING) {
                throw new InvalidLoginException();
            }


            $account = $user->getActiveAccount();

            if (!$account && $user->getAccounts()) {
                throw new AccountSuspendedException();
            }

            Session::instance()->setLoggedInUser($user);

        }

        if ($account) {

            if ($account->getStatus() == Account::STATUS_SUSPENDED) {
                throw new AccountSuspendedException();
            }

            Session::instance()->setLoggedInAccount($account);
        }
    }

}
