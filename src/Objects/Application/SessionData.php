<?php


namespace Kinicart\Objects\Application;

use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\AccountSummary;
use Kinicart\Objects\Security\User;
use Kinikit\Core\Object\SerialisableObject;

/**
 * Simple container containing common session data for return back to the
 * application for display purposes etc.
 *
 * @package Kinicart\Objects\Application
 */
class SessionData extends SerialisableObject {

    /**
     * @var User
     */
    private $user;

    /**
     * @var AccountSummary
     */
    private $account;


    /**
     * Get session data using user and account objects to seed the data.
     *
     * SessionData constructor.
     * @param User $user
     * @param Account $account
     */
    public function __construct($user = null, $account = null) {
        $this->user = $user;
        $this->account = $account ? $account->generateSummary() : null;
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @return AccountSummary
     */
    public function getAccount() {
        return $this->account;
    }


}
