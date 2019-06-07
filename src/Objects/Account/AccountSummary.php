<?php


namespace Kinicart\Objects\Account;


use Kinikit\Persistence\UPF\Object\ActiveRecord;

/**
 * Account summary.  Used for listing accounts in both Admin and for a user.
 *
 * @ormTable kc_account
 */
class AccountSummary extends ActiveRecord {

    /**
     * The account name - optional
     *
     * @var string
     */
    protected $name;


    /**
     * Auto increment id.  Strategically breaking naming convention to
     * enforce security based upon account id.
     *
     * @var integer
     * @primaryKey
     * @autoIncrement
     */
    protected $accountId;


    /**
     * @var integer
     */
    protected $parentAccountId;


    /**
     * Status of the account in question.
     *
     * @var string
     */
    protected $status = self::STATUS_ACTIVE;


    const STATUS_ACTIVE = "ACTIVE";
    const STATUS_SUSPENDED = "SUSPENDED";


    /**
     * @return string
     */
    public function getName() {
        return $this->name;
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
    public function getParentAccountId() {
        return $this->parentAccountId;
    }


    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }


}
