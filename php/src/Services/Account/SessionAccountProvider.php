<?php


namespace Kinicart\Services\Account;


use Kiniauth\Services\Application\Session;
use Kinicart\Objects\Account\Account;

/**
 * @noProxy
 */
class SessionAccountProvider implements AccountProvider {

    /**
     * @var Session
     */
    private $session;

    /**
     * Construct with session object
     *
     * @param Session $session
     */
    public function __construct($session) {
        $this->session = $session;
    }

    /**
     * Provide the account - either from explicit session or create a new one.
     *
     * @return Account
     */
    public function provideAccount() {
        return $this->session->__getLoggedInAccount() ?? new Account();
    }
}
