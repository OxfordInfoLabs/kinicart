<?php


namespace Kinicart\Objects\Account;


use Kinikit\Persistence\UPF\Object\ActiveRecord;


/**
 * Encodes a role for a user on an account.
 *
 * @ormTable kc_user_account_role
 *
 */
class UserAccountRole extends ActiveRecord {

    /**
     * The user id for which this account role is being attached
     *
     * @var integer
     * @primaryKey
     */
    protected $userId;

    /**
     * The account id for which this role is being attached.  If left blank this is assumed
     * to be a super user role
     *
     * @var integer
     * @primaryKey
     *
     */
    protected $accountId;


    /**
     * The role id to be attached to a user and account - if left blank this is assumed to mean all roles.
     *
     * @var integer
     * @primaryKey
     */
    protected $roleId;


    /**
     * The role object which this user account role represents.
     *
     * @var \Kinicart\Objects\Account\Role
     *
     * @relationship
     * @relatedClassName Kinicart\Objects\Security\Role
     * @relatedFields roleId=>id
     * @readOnly
     */
    protected $role;


    /**
     * The account summary object which this user account role is attached to.
     *
     * @var \Kinicart\Objects\Account\AccountSummary
     *
     * @relationship
     * @relatedClassName Kinicart\Objects\Account\AccountSummary
     * @relatedFields accountId=>id
     * @readOnly
     */
    protected $accountSummary;


    /**
     * Construct a new user account role object.
     *
     * @param integer $accountId
     * @param integer $roleId
     */
    public function __construct($accountId = null, $roleId = null, $userId = null) {
        $this->accountId = $accountId;
        $this->roleId = $roleId;
        $this->userId = $userId;
    }


    /**
     * @return int
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * @return int
     */
    public function getRoleId() {
        return $this->roleId;
    }

    /**
     * @return Role
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * @return AccountSummary
     */
    public function getAccountSummary() {
        return $this->accountSummary;
    }


}
