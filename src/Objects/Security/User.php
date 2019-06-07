<?php


namespace Kinicart\Objects\Security;


use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Application\Session;
use Kinikit\Core\Exception\ValidationException;
use Kinikit\Core\Object\SerialisableObject;
use Kinikit\Core\Util\SerialisableArrayUtils;
use Kinikit\Core\Validation\FieldValidationError;
use Kinikit\Persistence\UPF\Object\ActiveRecord;


/**
 * Main user entity for accessing the system.  Users typically belong to one or more accounts or are super users.
 *
 * @ormTable kc_user
 */
class User extends ActiveRecord {

    /**
     * Auto incremented id.
     *
     * @var integer
     */
    protected $id;


    /**
     * Email address (identifies the user within the system).
     *
     * @var string
     * @validation required
     */
    private $emailAddress;


    /**
     * The full name for this user.  May or may not be required depending on the application.
     *
     * @var string
     */
    private $name;

    /**
     * An optional parent account id, if this account has been created in the context of a
     * parent account.  This allows for multiple accounts for the same email address across multiple
     * contexts.
     *
     * @var string
     */
    private $parentAccountId;


    /**
     * Hashed password for interactive login checks
     *
     * @var string
     * @validation required
     */
    private $hashedPassword;


    /**
     * Optional mobile phone for extra security checks.
     *
     * @var string
     */
    private $mobileNumber;


    /**
     * Backup email address for extra security checks.
     *
     * @var string
     */
    private $backupEmailAddress;


    /**
     * Optional two factor authentication data if this has been enabled.
     *
     * @var string
     */
    private $twoFactorData;


    /**
     * An array of explicit user account role objects
     *
     * @var \Kinicart\Objects\Security\UserRole[]
     * @relationship
     * @isMultiple
     * @relatedClassName Kinicart\Objects\Security\UserRole
     * @relatedFields id=>userId
     *
     */
    private $roles = array();


    /**
     * Active account id.  This will default to the first account found for the
     * user based upon roles if not supplied.
     *
     * @var integer
     */
    private $activeAccountId;


    /**
     * Status for this user.
     *
     * @var string
     */
    private $status = self::STATUS_PENDING;


    const STATUS_PENDING = "PENDING";
    const STATUS_ACTIVE = "ACTIVE";
    const STATUS_SUSPENDED = "SUSPENDED";
    const STATUS_PASSWORD_RESET = "PASSWORD_RESET";


    /**
     * Create a new user with basic data.
     *
     * @param string $emailAddress
     * @param string $password
     * @param string $name
     */
    public function __construct($emailAddress = null, $password = null, $name = null, $parentAccountId = null) {
        $this->emailAddress = $emailAddress;
        if ($password) $this->hashedPassword = hash("md5", $password);
        $this->name = $name;
        $this->parentAccountId = $parentAccountId;
    }


    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmailAddress() {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress) {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function getParentAccountId() {
        return $this->parentAccountId;
    }

    /**
     * @param string $parentAccountId
     */
    public function setParentAccountId($parentAccountId) {
        $this->parentAccountId = $parentAccountId;
    }

    /**
     * @return string
     */
    public function getHashedPassword() {
        return $this->hashedPassword;
    }

    /**
     * @param string $hashedPassword
     */
    public function setHashedPassword($hashedPassword) {
        $this->hashedPassword = $hashedPassword;
    }

    /**
     * @return string
     */
    public function getTwoFactorData() {
        return $this->twoFactorData;
    }

    /**
     * @param string $twoFactorData
     */
    public function setTwoFactorData($twoFactorData) {
        $this->twoFactorData = $twoFactorData;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getMobileNumber() {
        return $this->mobileNumber;
    }

    /**
     * @param string $mobileNumber
     */
    public function setMobileNumber($mobileNumber) {
        $this->mobileNumber = $mobileNumber;
    }

    /**
     * @return string
     */
    public function getBackupEmailAddress() {
        return $this->backupEmailAddress;
    }

    /**
     * @param string $backupEmailAddress
     */
    public function setBackupEmailAddress($backupEmailAddress) {
        $this->backupEmailAddress = $backupEmailAddress;
    }


    /**
     * @return UserRole[]
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * @param UserRole[] $roles
     */
    public function setRoles($roles) {
        $this->roles = $roles;
    }


    public function getAccountIds() {
        $accountIds = array();
        foreach ($this->roles as $role) {
            if ($role->getScopeId())
                $accountIds[$role->getScopeId()] = 1;
        }
        return array_keys($accountIds);
    }


    /**
     * @return int
     */
    public function getActiveAccountId() {
        $firstActiveAccountId = null;

        $rolesByAccountId = SerialisableArrayUtils::indexArrayOfObjectsByMember("accountId", $this->getRoles());

        foreach ($rolesByAccountId as $role) {

            if ($role->getAccountStatus() == Account::STATUS_ACTIVE) {
                if ($this->activeAccountId == $role->getAccountId())
                    return $role->getAccountId();

                if (!$firstActiveAccountId)
                    $firstActiveAccountId = $role->getAccountId();

            }
        }

        return $firstActiveAccountId;
    }

    /**
     * @param int $activeAccountId
     */
    public function setActiveAccountId($activeAccountId) {
        $this->activeAccountId = $activeAccountId;
    }


    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * Handle more advanced checking for no overlap of email addresses in same context
     *
     * @return array
     */
    public function validate() {
        $validationErrors = parent::validate();

        // Check for duplication across parent accounts
        $matchingUsers = self::countQuery("WHERE emailAddress = ? AND parent_account_id = ? AND id <> ?", $this->emailAddress,
            $this->parentAccountId ? $this->parentAccountId : 0, $this->id ? $this->id : -1);

        if ($matchingUsers > 0)
            $validationErrors["emailAddress"] = new FieldValidationError("emailAddress", "duplicateEmail", "A user already exists with this email address");

        return $validationErrors;
    }


}
