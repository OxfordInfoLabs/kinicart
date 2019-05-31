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
     * Auto increment id.
     *
     * @var integer
     */
    protected $id;


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
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }


}
