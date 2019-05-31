<?php


namespace Kinicart\Objects\Account;


use Kinicart\Objects\Application\Session;
use Kinikit\Core\Exception\ValidationException;
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
     * An array of role objects.
     *
     * @var \Kinicart\Objects\Account\UserAccountRole[]
     * @relationship
     * @isMultiple
     * @relatedClassName Kinicart\Objects\Account\UserAccountRole
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
        $this->parentAccountId = $parentAccountId ? $parentAccountId : Session::instance()->getActiveParentAccountId();
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
     * @return UserAccountRole[]
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * @param UserAccountRole[] $roles
     */
    public function setRoles($roles) {
        $this->roles = $roles;
    }


    /**
     * Get an array of account summary objects for which this user has one or more roles.
     *
     * @return AccountSummary[]
     */
    public function getAccounts() {
        $accounts = array();
        if (is_array($this->roles)) {
            foreach ($this->roles as $role) {
                if ($role->getAccountSummary()) {
                    $accounts[$role->getAccountId()] = $role->getAccountSummary();
                }
            }
        }
        return array_values($accounts);
    }


    /**
     * Get the active account by looking up in our summary objects.
     */
    public function getActiveAccount() {
        $firstActiveAccount = null;
        foreach ($this->getAccounts() as $account) {
            if ($account->getStatus() == Account::STATUS_ACTIVE) {
                if ($this->activeAccountId == $account->getId())
                    return $account;

                if (!$firstActiveAccount)
                    $firstActiveAccount = $account;

            }
        }

        return $firstActiveAccount;
    }

    /**
     * @return int
     */
    public function getActiveAccountId() {
        return $this->activeAccountId;
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


    /**
     * Create a brand new user - optionally supply a name, account name and parent account id if relevant.  If no
     * parent Account Id is supplied, the session context will be used.
     */
    public static function createWithAccount($emailAddress, $password, $name = null, $accountName = null, $parentAccountId = null) {

        // Create a new user, save it and return it back.
        $user = new User($emailAddress, $password, $name, $parentAccountId);
        if ($validationErrors = $user->validate()) {
            throw new ValidationException($validationErrors);
        }

        // Create an account to match with any name we can find.
        $account = new Account($accountName ? $accountName : ($name ? $name : $emailAddress), $parentAccountId);
        $account->save();

        $user->setRoles(array(new UserAccountRole($account->getId())));
        $user->save();

        // Resync the user object
        $user->synchroniseRelationships();

        return $user;

    }


    /**
     * Create an admin user.
     *
     * @param $emailAddress
     * @param $password
     * @param null $name
     */
    public static function createAdminUser($emailAddress, $password, $name = null) {

        // Create a new user, save it and return it back.
        $user = new User($emailAddress, $password, $name);
        if ($validationErrors = $user->validate()) {
            throw new ValidationException($validationErrors);
        }

        $user->setRoles(array(new UserAccountRole()));
        $user->save();

        // Resync the user object
        $user->synchroniseRelationships();

        return $user;
    }


}
