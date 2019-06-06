<?php


namespace Kinicart\Services\Security;


use Kinicart\Exception\Application\AccountSuspendedException;
use Kinicart\Exception\Application\InvalidAPICredentialsException;
use Kinicart\Exception\Application\InvalidLoginException;
use Kinicart\Exception\Application\UserSuspendedException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\AccountSummary;
use Kinicart\Objects\Account\User;
use Kinicart\Objects\Application\Session;
use Kinikit\Core\Util\SerialisableArrayUtils;
use Kinikit\Persistence\Database\Connection\DefaultDB;

/**
 * AuthenticationService object for coordinating authentication functions for Kinicart.
 *
 * Class AuthenticationService
 * @package Kinicart\Workers\Application
 */
class AuthenticationService {

    private $settingsService;
    private $session;

    /**
     * @param \Kinicart\Services\Application\SettingsService $settingsService
     * @param \Kinicart\Services\Application\Session $session
     */
    public function __construct($settingsService, $session) {
        $this->settingsService = $settingsService;
        $this->session = $session;
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
    public function logIn($emailAddress, $password, $parentAccountId = null) {

        if ($parentAccountId === null) {
            $parentAccountId = $this->session->__getActiveParentAccountId() ? $this->session->__getActiveParentAccountId() : 0;
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
    public function logOut() {
        $this->session->__setLoggedInUser(null);
        $this->session->__setLoggedInAccount(null);
        $this->session->__setLoggedInPrivileges(null);
    }


    /**
     * Get all privileges for a given account id.  If no account id is passed in, the currently logged in
     * account privileges are returned.
     *
     * @param integer $accountId
     *
     * @return string[]
     */
    public function getLoggedInPrivileges($accountId = null) {
        $allPrivileges = $this->session->__getLoggedInPrivileges();

        // Merge any global privileges in.
        $privileges = array();
        if (isset($allPrivileges["*"])) {
            $privileges = $allPrivileges["*"];
        }

        // Revert back to logged in account if possible.
        if (!$accountId && $this->session->__getLoggedInAccount()) {
            $accountId = $this->session->__getLoggedInAccount()->getId();
        }

        if ($accountId) {
            if (isset($allPrivileges[$accountId]))
                $privileges = array_merge($privileges, $allPrivileges[$accountId]);
        }

        return array_keys($privileges);

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

            $this->session->__setLoggedInUser($user);


        }

        if ($account) {

            if ($account->getStatus() == Account::STATUS_SUSPENDED) {
                throw new AccountSuspendedException();
            }

            $this->session->__setLoggedInAccount($account);
        }


        $this->setLoggedInPrivileges($user, $account);


    }


    /**
     * Set logged in privileges according to whether or not we have a logged in user
     * or account.
     *
     * @param User $user
     * @param Account $account
     */
    private function setLoggedInPrivileges($user, $account) {

        $privileges = array();
        $accountIds = array();
        $superUser = false;

        if ($user) {


            foreach ($user->getRoles() as $role) {

                // Determine our core roles.
                if (is_numeric($role->getAccountId())) {

                    if ($role->getAccountId() == 0) {
                        $accountId = "*";
                        $superUser = true;
                    } else {
                        $accountId = $role->getAccountId();
                        $accountIds[$accountId] = 1;
                    }
                    if (!isset($privileges[$accountId])) {
                        $privileges[$accountId] = array();
                    }

                    if ($role->getRoleId()) {
                        foreach ($role->getRole()->getPrivileges() as $privilege)
                            $privileges[$accountId][$privilege] = 1;
                    } else {
                        $privileges[$accountId][$accountId == "*" ? "superuser" : "administrator"] = 1;
                    }

                }

            }

        } else if ($account) {
            $privileges[$account->getId()]["administrator"] = 1;
            $accountIds[$account->getId()] = 1;
        }

        // If we have at least one account, check for child accounts and add privileges for these.
        if (!$superUser && sizeof($accountIds) > 0) {
            $childAccounts = AccountSummary::query("WHERE parent_account_id IN (" . join(",", $accountIds) . ")");
            foreach ($childAccounts as $childAccount) {
                $privileges[$childAccount->getId()]["access"] = 1;
            }
        }

        $this->session->__setLoggedInPrivileges($privileges);

    }


}
