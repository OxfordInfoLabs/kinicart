<?php


namespace Kinicart\Objects\Account;


use Kinikit\Persistence\UPF\Object\ActiveRecord;


/**
 * Encodes a role for a user on an account.
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
     * The role id to be attached to a user and account.
     *
     * @var integer
     * @primaryKey
     */
    protected $roleId;


    /**
     * @var \Kinicart\Objects\Account\Role
     *
     * @relationship
     * @relatedClassName Kinicart\Objects\Account\Role
     * @relatedFields roleId=>id
     * @readOnly
     */
    protected $role;

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


}
