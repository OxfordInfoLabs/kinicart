<?php

namespace Kinicart\Objects\Account;


/**
 * Main account business object.  Users can belong to one or more accounts.
 *
 * Class Account
 *
 * @ormTable kc_account
 *
 */
class Account extends AccountSummary {


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

    /**
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }


}
