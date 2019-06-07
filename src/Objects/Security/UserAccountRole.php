<?php


namespace Kinicart\Objects\Security;


use Kinikit\Persistence\UPF\Object\ActiveRecord;


/**
 * Encodes a role for a user on an account.
 *
 * @ormTable kc_user_account_role
 * @ormView kc_vw_user_account_role
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
     * The read only array of privileges from the role object
     *
     * @var string[]
     * @formatter json
     * @readOnly
     */
    protected $privileges;


    /**
     * Read only status for the linked account
     *
     * @var string
     * @readOnly
     */
    protected $accountStatus;


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
     * @return string[]
     */
    public function getPrivileges() {
        return $this->privileges;
    }

    /**
     * @return string
     */
    public function getAccountStatus() {
        return $this->accountStatus;
    }


}
