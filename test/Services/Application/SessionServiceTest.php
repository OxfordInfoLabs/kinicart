<?php

namespace Kinicart\Test\Services\Application;

use Kinicart\Services\Application\SessionService;
use Kinicart\Services\Security\AuthenticationService;
use Kinicart\Services\Security\SecurityService;
use Kinicart\Test\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once __DIR__ . "/../../autoloader.php";

/**
 * Class SessionServiceTest
 */
class SessionServiceTest extends TestBase {

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var SessionService
     */
    private $sessionService;


    /**
     * @var SecurityService
     */
    private $securityService;


    public function setUp(): void {
        parent::setUp();
        $this->authenticationService = Container::instance()->get(AuthenticationService::class);
        $this->sessionService = Container::instance()->get(SessionService::class);
        $this->securityService = Container::instance()->get(SecurityService::class);
    }


    public function testCanGetSessionData() {

        // Super Admin
        $this->authenticationService->login("admin@kinicart.com", "password");
        $sessionData = $this->sessionService->getSessionData();
        $loggedIn = $this->securityService->getLoggedInUserAndAccount();
        $this->assertEquals($loggedIn[0], $sessionData->getUser());
        $this->assertNull($sessionData->getAccount());


        // Account admin
        $this->authenticationService->login("sam@samdavisdesign.co.uk", "password");

        $sessionData = $this->sessionService->getSessionData();
        $loggedIn = $this->securityService->getLoggedInUserAndAccount();
        $this->assertEquals($loggedIn[0], $sessionData->getUser());
        $this->assertEquals($loggedIn[1]->generateSummary(), $sessionData->getAccount());

        // Api one
        $this->authenticationService->apiAuthenticate("TESTAPIKEY", "TESTAPISECRET");

        $sessionData = $this->sessionService->getSessionData();
        $loggedIn = $this->securityService->getLoggedInUserAndAccount();
        $this->assertNull($sessionData->getUser());
        $this->assertEquals($loggedIn[1]->generateSummary(), $sessionData->getAccount());


    }

}
