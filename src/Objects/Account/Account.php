<?php

namespace Kinicart\Objects\Account;


use Kinikit\Persistence\UPF\Object\ActiveRecord;

/**
 * Main account business object.  Users can belong to one or more accounts.
 *
 * Class Account
 */
class Account extends ActiveRecord {


    /**
     * Auto increment id.
     *
     * @var integer
     */
    protected $id;


    /**
     * The account name - optional
     *
     * @var string
     */
    private $name;


    /**
     * Boolean indicating whether or not this account can create sub accounts.
     *
     * @var boolean
     */
    private $subAccountsEnabled;


    /**
     * Id of a parent account if this is a sub account
     *
     * @var integer
     */
    private $parentAccountId;


    /**
     * @return int
     */
    public function getId() {
        return $this->id;
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
     * @return bool
     */
    public function getSubAccountsEnabled() {
        return $this->subAccountsEnabled;
    }

    /**
     * @param bool $subAccountsEnabled
     */
    public function setSubAccountsEnabled($subAccountsEnabled) {
        $this->subAccountsEnabled = $subAccountsEnabled;
    }

    /**
     * @return int
     */
    public function getParentAccountId() {
        return $this->parentAccountId;
    }

    /**
     * @param int $parentAccountId
     */
    public function setParentAccountId($parentAccountId) {
        $this->parentAccountId = $parentAccountId;
    }


}
