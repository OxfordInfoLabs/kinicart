<?php

namespace Kinicart\Objects\Account;


use Kinicart\Objects\Application\Session;
use Kinikit\Core\Util\StringUtils;

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
     * API key for account access
     *
     * @var string
     */
    private $apiKey;

    /**
     * API secret for account access
     *
     * @var string
     */
    private $apiSecret;


    // Logged in account constant for default value usage.
    const LOGGED_IN_ACCOUNT = "LOGGED_IN_ACCOUNT";


    /**
     * Construct an account
     *
     * Account constructor.
     */
    public function __construct($name = null, $parentAccountId = null) {
        $this->name = $name;
        $this->parentAccountId = $parentAccountId;

        $this->apiKey = StringUtils::generateRandomString(10, true, true, false);
        $this->apiSecret = StringUtils::generateRandomString(10, true, true, false);
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

    /**
     * @return string
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getApiSecret() {
        return $this->apiSecret;
    }

    /**
     * @param string $apiSecret
     */
    public function setApiSecret($apiSecret) {
        $this->apiSecret = $apiSecret;
    }


}
