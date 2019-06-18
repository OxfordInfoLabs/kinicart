<?php


namespace Kinicart\Services\Application;


use Kinicart\Objects\Application\SessionData;

class SessionService {

    private $session;

    /**
     * Construct with authentication service
     *
     * @param \Kinicart\Services\Application\Session $session
     */
    public function __construct($session) {
        $this->session = $session;
    }


    /**
     * Get session data for current logged in state.
     */
    public function getSessionData() {
        return new SessionData($this->session->__getLoggedInUser(), $this->session->__getLoggedInAccount());
    }

}
