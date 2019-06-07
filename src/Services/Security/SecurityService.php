<?php


namespace Kinicart\Services\Security;


use Kinicart\Exception\Application\AccountSuspendedException;
use Kinicart\Exception\Application\InvalidLoginException;
use Kinicart\Exception\Application\UserSuspendedException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\AccountSummary;
use Kinicart\Objects\Security\Privilege;
use Kinicart\Objects\Security\User;

class SecurityService {

    private $session;

    /**
     * @param \Kinicart\Services\Application\Session $session
     */
    public function __construct($session) {
        $this->session = $session;
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


    /**
     * Login as either a user or an account.  This should usually be called from
     * an Authentication service.  It sets up the session variables required to maintain state.
     *
     * @param User $user
     * @param Account $account
     * @throws AccountSuspendedException
     * @throws InvalidLoginException
     * @throws UserSuspendedException
     */
    public function login($user = null, $account = null) {

        $this->logout();


        $accountId = null;

        if ($user) {


            // Throw suspended exception if user is suspended.
            if ($user->getStatus() == User::STATUS_SUSPENDED) {
                throw new UserSuspendedException();
            }

            // Throw invalid login if still pending.
            if ($user->getStatus() == User::STATUS_PENDING) {
                throw new InvalidLoginException();
            }


            $accountId = $user->getActiveAccountId();

            if (!$accountId && $user->getAccountIds()) {
                throw new AccountSuspendedException();
            }

            $this->session->__setLoggedInUser($user);


        }

        if ($account) {

            if ($account->getStatus() == Account::STATUS_SUSPENDED) {
                throw new AccountSuspendedException();
            }

            $accountId = $account->getId();
        }


        // If an accountId, read it and store it.
        if ($accountId) {
            $account = AccountSummary::fetch($accountId);
            $this->session->__setLoggedInAccount($account);
        }


        $this->setLoggedInPrivileges($user, $account);


    }


    /**
     * Log out implementation.  Usually called from authentication service.
     */
    public function logout() {
        // Clean down the session to remove any previously logged in state
        $this->session->__setLoggedInUser(null);
        $this->session->__setLoggedInAccount(null);
        $this->session->__setLoggedInPrivileges(null);
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
                        foreach ($role->getPrivileges() as $privilege)
                            $privileges[$accountId][$privilege] = 1;
                    } else {
                        $privileges[$accountId][$accountId == "*" ? Privilege::PRIVILEGE_SUPER_USER : Privilege::PRIVILEGE_ADMINISTRATOR] = 1;
                    }

                }

            }

        } else if ($account) {
            $privileges[$account->getId()][Privilege::PRIVILEGE_ADMINISTRATOR] = 1;
            $accountIds[$account->getId()] = 1;
        }

        // If we have at least one account, check for child accounts and add privileges for these.
        if (!$superUser && sizeof($accountIds) > 0) {
            $childAccounts = AccountSummary::query("WHERE parent_account_id IN (" . join(",", $accountIds) . ")");
            foreach ($childAccounts as $childAccount) {
                $privileges[$childAccount->getId()][Privilege::PRIVILEGE_ACCESS] = 1;
            }
        }

        $this->session->__setLoggedInPrivileges($privileges);

    }


}
