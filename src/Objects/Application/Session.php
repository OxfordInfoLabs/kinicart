<?php


namespace Kinicart\Objects\Application;

use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\User;
use Kinikit\Core\Util\HTTP\HttpSession;

/**
 * Kinicart session object.  This subclasses the Kinikit HTTP Session object to provide
 * nice access to the core session items required for Kinicart.
 *
 * Class Session
 * @package Kinicart\Objects\Application
 */
class Session extends HttpSession {

    /**
     * Get the logged in user
     *
     * @return User
     */
    public function getLoggedInUser() {
        return $this->getValue("loggedInUser");
    }


    /**
     * Set the logged in user
     *
     * @param $user User
     */
    public function setLoggedInUser($user) {
        $this->setValue("loggedInUser", $user);
    }


    /**
     * Get the logged in account
     *
     * @return Account
     */
    public function getLoggedInAccount() {
        return $this->getValue("loggedInAccount");
    }


    /**
     * Set the logged in account
     *
     * @param $account
     */
    public function setLoggedInAccount($account) {
        $this->setValue("loggedInAccount", $account);
    }


    /**
     * Get the referring URL
     *
     * @return $string
     */
    public function getReferringURL() {
        return $this->getValue("referringURL");
    }


    /**
     * Set the referring URL.
     *
     * @param $referringURL
     */
    public function setReferringURL($referringURL) {
        $this->setValue("referringURL", $referringURL);
    }


    /**
     * Get the active parent account Id.
     *
     * @return integer
     */
    public function getActiveParentAccountId() {
        return $this->getValue("activeParentAccountId");
    }


    /**
     * Set the active parent account Id.
     *
     * @param $activeParentAccountId
     */
    public function setActiveParentAccountId($activeParentAccountId) {
        $this->setValue("activeParentAccountId", $activeParentAccountId);
    }


    /**
     * Log out function.
     */
    public function logOut() {
        $this->setValue("loggedInUser", null);
        $this->setValue("loggedInAccount", null);
    }


    /**
     * Enforce a singleton session object
     *
     * @return Session
     */
    public static function instance() {
        return parent::instance();
    }

}
