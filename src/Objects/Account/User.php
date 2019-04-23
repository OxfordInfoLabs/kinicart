<?php


namespace Kinicart\Objects\Account;


use Kinikit\Persistence\UPF\Object\ActiveRecord;


/**
 * Main user entity for accessing the system.  Users typically belong to one or more accounts or are super users.
 *
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
     * An optional second context identifier for this user if we wish to allow multiple users with
     * the same email address e.g. when supporting multiple sites in the same system.
     *
     * @var string
     */
    private $contextKey;


    /**
     * Hashed password for interactive login checks
     *
     * @var string
     * @validation required
     */
    private $hashedPassword;


    /**
     * Optional two factor authentication data if this has been enabled.
     *
     * @var string
     */
    private $twoFactorData;


    /**
     * The full name for this user.  May or may not be required depending on the application.
     *
     * @var string
     */
    private $name;


    /**
     * An array of role objects.
     *
     * @var \Kinicart\Objects\Account\UserAccountRole[]
     * @relationship
     * @isMultiple
     * @relatedClassName \Kinicart\Objects\Account\UserAccountRole
     * @relatedFields id=>userId
     *
     */
    private $roles;

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
    public function getContextKey() {
        return $this->contextKey;
    }

    /**
     * @param string $contextKey
     */
    public function setContextKey($contextKey) {
        $this->contextKey = $contextKey;
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
     * @return UserAccountRole[]
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * @param Role[] $roles
     */
    public function setRoles($roles) {
        $this->roles = $roles;
    }


}
