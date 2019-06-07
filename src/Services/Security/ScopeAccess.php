<?php


namespace Kinicart\Services\Security;


use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Security\User;
use Kinicart\Objects\Security\UserRole;

/**
 * Scope access objects allow for configuration of both the Object and Method interceptors for a given scope.
 *
 * Class ScopeAccess
 */
abstract class ScopeAccess {


    /**
     * The scope of this access object
     *
     * @var string
     */
    private $scope;

    /**
     * The object member which will be checked for in object / method interceptors
     * for this scope access.
     *
     * @var string
     */
    private $objectMember;


    /**
     * Construct with a scope string
     *
     * @param $scope
     */
    public function __construct($scope, $objectMember = null) {
        $this->scope = $scope;
        $this->objectMember = $objectMember;
    }

    /**
     * @return string
     */
    public function getScope() {
        return $this->scope;
    }

    /**
     * @return string
     */
    public function getObjectMember() {
        return $this->objectMember;
    }


    /**
     * Generate scope privileges from either a user or an account (only one will be passed).
     * if an account is passed, it means it is an account based log in so will generally be granted full access to account items.
     *
     * This is used on log in to determine access to items of this scope type.  It should return an array of privilege keys indexed by the id of the
     * scope item.  The indexed array of account privileges is passed through for convenience.
     *
     * Use * as the scope key to indicate all accounts.
     *
     * @param User $user
     * @param Account $account
     * @param string[] $accountPrivileges
     *
     * @return
     */
    public abstract function generateScopePrivileges($user, $account, $accountPrivileges);


}
