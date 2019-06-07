<?php


namespace Kinicart\Services\Security;


use Kinicart\Exception\Application\AccountSuspendedException;
use Kinicart\Exception\Application\InvalidLoginException;
use Kinicart\Exception\Application\UserSuspendedException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\AccountSummary;
use Kinicart\Objects\Security\Role;
use Kinicart\Objects\Security\User;

class SecurityService {

    private $session;
    private $accountScopeAccess;

    /**
     * @var ScopeAccess[]
     */
    private $scopeAccesses;

    /**
     * @param \Kinicart\Services\Application\Session $session
     * @param \Kinicart\Services\Security\AccountScopeAccess $accountScopeAccess
     */
    public function __construct($session, $accountScopeAccess) {
        $this->session = $session;
        $this->scopeAccesses = [$accountScopeAccess];
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

            $accountId = $account->getAccountId();
        }


        // If an accountId, read it and store it.
        if ($accountId) {
            $account = AccountSummary::fetch($accountId);
            $this->session->__setLoggedInAccount($account);
        }

        /**
         * Process all scope accesses and build the global privileges array
         */
        $privileges = array();

        // Add account scope access
        $accountPrivileges = null;
        foreach ($this->scopeAccesses as $scopeAccess) {
            $scopePrivileges = $scopeAccess->generateScopePrivileges($user, $account, $accountPrivileges);
            $privileges[$scopeAccess->getScope()] = $scopePrivileges;
            if ($scopeAccess->getScope() == Role::SCOPE_ACCOUNT) $accountPrivileges = $scopePrivileges;
        }

        $this->session->__setLoggedInPrivileges($privileges);

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
     * Verify whether or not a logged in user can access an object by checking all available installed scope accesses.
     *
     * @param $object
     */
    public function checkLoggedInObjectAccess($object) {

        $accessors = $object->__findSerialisablePropertyAccessors();

        $access = true;
        foreach ($this->scopeAccesses as $scopeAccess) {
            $objectMember = $scopeAccess->getObjectMember();
            if ($objectMember && isset($accessors[strtolower($objectMember)])) {
                $scopeId = $object->__getSerialisablePropertyValue($objectMember);
                $access = $access && $this->getLoggedInScopePrivileges($scopeAccess->getScope(), $scopeId);
            }
            if (!$access)
                break;
        }

        return $access;

    }


    /**
     * Get all privileges for a given account id.  If no account id is passed in, the currently logged in
     * account privileges are returned.
     *
     * @param integer $accountId
     *
     * @return string[]
     */
    public function getLoggedInScopePrivileges($scope, $scopeId) {

        $allPrivileges = $this->session->__getLoggedInPrivileges();

        // Merge any global privileges in.
        $privileges = array();
        if (isset($allPrivileges[$scope]["*"])) {
            $privileges = $allPrivileges[$scope]["*"];
        }

        if (isset($allPrivileges[$scope][$scopeId]))
            $privileges = $privileges + $allPrivileges[$scope][$scopeId];


        return $privileges;

    }


}
