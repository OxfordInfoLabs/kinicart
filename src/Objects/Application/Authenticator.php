<?php


namespace Kinicart\Objects\Application;


use Kinicart\Exception\Application\AccountSuspendedException;
use Kinicart\Exception\Application\InvalidLoginException;
use Kinicart\Exception\Application\UserSuspendedException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\User;

/**
 * Authenticator object for coordinating authentication functions for Kinicart.
 *
 * Class AuthenticationWorker
 * @package Kinicart\Workers\Application
 */
class Authenticator {

    /**
     * @var $instance Authenticator
     */
    private static $instance;

    // Block direct construction.
    private function __construct() {
    }


    /**
     * Static instance method in lieu of constructor.
     *
     * @return Authenticator
     */
    public static function instance() {
        if (!self::$instance) {
            self::$instance = new Authenticator();
        }

        return self::$instance;
    }


    /**
     * Boolean indicator as to whether or not an email address exists.
     *
     * @param $emailAddress
     * @param null $contextKey
     */
    public function emailExists($emailAddress, $contextKey = null) {
        return User::countQuery("WHERE emailAddress = ?", $emailAddress) > 0;
    }


    /**
     * Log in with an email address and password.
     *
     * @param $emailAddress
     * @param $password
     */
    public function logIn($emailAddress, $password, $contextKey = null) {

        $matchingUsers = User::query("WHERE emailAddress = ? AND hashedPassword = ?", $emailAddress, hash("md5", $password));

        // If there is a matching user, return it now.
        if (sizeof($matchingUsers) > 0) {
            $this->setLoggedInDetails($matchingUsers[0]);
        } else {
            throw new InvalidLoginException();
        }


    }


    /**
     * Log out function.
     */
    public function logOut() {
        Session::instance()->logOut();
    }


    // Set logged in details (either a user or an account if an API login).
    private function setLoggedInDetails($user = null, $account = null) {

        if ($user) {


            // Throw suspended exception if user is suspended.
            if ($user->getStatus() == User::STATUS_SUSPENDED) {
                throw new UserSuspendedException();
            }

            // Throw invalid login if still pending.
            if ($user->getStatus() == User::STATUS_PENDING) {
                throw new InvalidLoginException();
            }


            $account = $user->getActiveAccount();

            if (!$account && $user->getAccounts()) {

            }
            
            Session::instance()->setLoggedInUser($user);

        }

        if ($account) {
            Session::instance()->setLoggedInAccount($account);
        }
    }

}
