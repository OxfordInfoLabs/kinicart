<?php


namespace Kinicart\WebServices\ControllerTraits\Guest;


use Kinicart\Objects\Application\SessionData;


trait Session {

    private $sessionService;

    /**
     * @param \Kinicart\Services\Application\SessionService $sessionService
     */
    public function __construct($sessionService) {
        $this->sessionService = $sessionService;
    }

    /**
     * Return the logged in user/account
     *
     * @http GET /
     *
     * @return SessionData
     */
    public function getSessionData() {
        return $this->sessionService->getSessionData();
    }

}
