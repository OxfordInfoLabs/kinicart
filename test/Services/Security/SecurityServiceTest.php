<?php


namespace Kinicart\Test\Services\Security;


use Kinicart\Test\TestBase;
use Kinikit\Core\DependencyInjection\Container;

class SecurityServiceTest extends TestBase {

    private $securityService;
    private $authenticationService;

    public function setUp() {
        parent::setUp();
        $this->authenticationService = Container::instance()->get("Kinicart\Services\Security\AuthenticationService");
        $this->securityService = Container::instance()->get("Kinicart\Services\Security\SecurityService");
    }


    public function testCanGetLoggedInPrivilegesForAnAccount() {

        // Logged out
        $this->authenticationService->logout();

        $this->assertEquals(array(), $this->securityService->getLoggedInPrivileges(1));
        $this->assertEquals(array(), $this->securityService->getLoggedInPrivileges(2));
        $this->assertEquals(array(), $this->securityService->getLoggedInPrivileges(3));


        // Super user.
        $this->authenticationService->login("admin@kinicart.com", "password");

        $this->assertEquals(array("superuser"), $this->securityService->getLoggedInPrivileges(1));
        $this->assertEquals(array("superuser"), $this->securityService->getLoggedInPrivileges(2));
        $this->assertEquals(array("superuser"), $this->securityService->getLoggedInPrivileges());


        // Account admin
        $this->authenticationService->login("sam@samdavisdesign.co.uk", "password");

        $this->assertEquals(array("administrator"), $this->securityService->getLoggedInPrivileges(1));
        $this->assertEquals(array(), $this->securityService->getLoggedInPrivileges(2));
        $this->assertEquals(array(), $this->securityService->getLoggedInPrivileges(3));


        // User with dual account admin access.
        $this->authenticationService->login("mary@shoppingonline.com", "password");
        $this->assertEquals(array(), $this->securityService->getLoggedInPrivileges(1));
        $this->assertEquals(array("viewdata", "editdata", "deletedata"), $this->securityService->getLoggedInPrivileges(2));
        $this->assertEquals(array(), $this->securityService->getLoggedInPrivileges(3));
        $this->assertEquals(array("viewdata", "editdata"), $this->securityService->getLoggedInPrivileges(4));


        // User with sub accounts.
        $this->authenticationService->login("sam@samdavisdesign.co.uk", "password");
        $this->assertEquals(array("access"), $this->securityService->getLoggedInPrivileges(5));
        $this->assertEquals(array("access"), $this->securityService->getLoggedInPrivileges(9));


        // Account logged in by API
        $this->authenticationService->apiAuthenticate("TESTAPIKEY", "TESTAPISECRET");
        $this->assertEquals(array("administrator"), $this->securityService->getLoggedInPrivileges());
        $this->assertEquals(array("access"), $this->securityService->getLoggedInPrivileges(5));
        $this->assertEquals(array("access"), $this->securityService->getLoggedInPrivileges(9));

    }

}
