<?php


namespace Kinicart\Test\Services\Security;


use Kinicart\Objects\Account\Contact;
use Kinicart\Objects\Security\Role;
use Kinicart\Services\Security\SecurityService;
use Kinicart\Test\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once __DIR__ . "/../../autoloader.php";

class SecurityServiceTest extends TestBase {

    /**
     * @var SecurityService
     */
    private $securityService;
    private $authenticationService;

    public function setUp() {
        parent::setUp();
        $this->authenticationService = Container::instance()->get("Kinicart\Services\Security\AuthenticationService");
        $this->securityService = Container::instance()->get("Kinicart\Services\Security\SecurityService");
    }


    public function testCanGetLoggedInAccountScopePrivileges() {

        // Logged out
        $this->authenticationService->logout();

        $this->assertEquals(array(), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 1));
        $this->assertEquals(array(), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 2));
        $this->assertEquals(array(), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 3));


        // Super user.
        $this->authenticationService->login("admin@kinicart.com", "password");

        $this->assertEquals(array("*"), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 1));
        $this->assertEquals(array("*"), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 2));


        // Account admin
        $this->authenticationService->login("sam@samdavisdesign.co.uk", "password");

        $this->assertEquals(array("*"), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 1));
        $this->assertEquals(array(), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 2));
        $this->assertEquals(array(), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 3));


        // User with dual account admin access.
        $this->authenticationService->login("mary@shoppingonline.com", "password");
        $this->assertEquals(array(), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 1));
        $this->assertEquals(array("viewdata", "editdata", "deletedata"), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 2));
        $this->assertEquals(array(), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 3));
        $this->assertEquals(array("viewdata", "editdata"), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 4));


        // User with sub accounts.
        $this->authenticationService->login("sam@samdavisdesign.co.uk", "password");
        $this->assertEquals(array("*"), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 5));
        $this->assertEquals(array("*"), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 9));


        // Account logged in by API
        $this->authenticationService->apiAuthenticate("TESTAPIKEY", "TESTAPISECRET");
        $this->assertEquals(array("*"), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 1));
        $this->assertEquals(array("*"), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 5));
        $this->assertEquals(array("*"), $this->securityService->getLoggedInScopePrivileges(Role::SCOPE_ACCOUNT, 9));

    }

    public function testCanCheckObjectAccessWithAccountId() {

        $contact = new Contact("Mark R", "Test Organisation", "My Lane", "My Shire", "Oxford",
            "Oxon", "OX4 7YY", "GB", null, "test@test.com", 1, Contact::ADDRESS_TYPE_GENERAL);


        // Logged out
        $this->authenticationService->logout();
        $this->assertFalse($this->securityService->checkLoggedInObjectAccess($contact));

        // Super user
        $this->authenticationService->login("admin@kinicart.com", "password");
        $this->assertTrue($this->securityService->checkLoggedInObjectAccess($contact));

        // User with different account access
        $this->authenticationService->login("mary@shoppingonline.com", "password");
        $this->assertFalse($this->securityService->checkLoggedInObjectAccess($contact));

        // API login
        $this->authenticationService->apiAuthenticate("TESTAPIKEY", "TESTAPISECRET");
        $this->assertTrue($this->securityService->checkLoggedInObjectAccess($contact));



    }

}
