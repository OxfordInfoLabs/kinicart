<?php

namespace Kinicart\Test\Objects\Application;

use Kinicart\Exception\Application\AccountSuspendedException;
use Kinicart\Exception\Application\InvalidAPICredentialsException;
use Kinicart\Exception\Application\InvalidLoginException;
use Kinicart\Exception\Application\UserSuspendedException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\AccountSummary;
use Kinicart\Objects\Account\UserAccountRole;
use Kinicart\Objects\Security\User;
use Kinicart\Services\Security\AuthenticationService;
use Kinicart\Services\Application\BootstrapService;
use Kinicart\Services\Application\Session;
use Kinicart\Test\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once __DIR__ . "/../../autoloader.php";

class AuthenticationServiceTest extends TestBase {

    /**
     * @var AuthenticationService
     */
    private $authenticationService;
    private $bootstrapService;

    /**
     * @var Session
     */
    private $session;


    public function setUp() {

        parent::setUp();

        $this->authenticationService = Container::instance()->get("Kinicart\Services\Security\AuthenticationService");
        $this->bootstrapService = Container::instance()->get("Kinicart\Services\Application\BootstrapService");
        $this->session = Container::instance()->get("Kinicart\Services\Application\Session");
    }

    public function testCanCheckWhetherEmailExistsOrNot() {

        $this->assertFalse($this->authenticationService->emailExists("james@test.com"));
        $this->assertFalse($this->authenticationService->emailExists("bobby@wrong.test"));
        $this->assertTrue($this->authenticationService->emailExists("admin@kinicart.com"));

    }


    /**
     * Check we can authenticate as a super user.
     */
    public function testCanLoginAsSuperUser() {

        // Attempt a login.
        $this->authenticationService->login("admin@kinicart.com", "password");

        // Confirm that we are now logged in
        $this->assertNull($this->session->__getLoggedInAccount());

        $loggedInUser = $this->session->__getLoggedInUser();
        $this->assertTrue($loggedInUser instanceof User);

        $this->assertEquals(1, $loggedInUser->getId());
        $this->assertEquals("Administrator", $loggedInUser->getName());
        $this->assertEquals(1, sizeof($loggedInUser->getRoles()));

        // Check the logged in privileges are set correctly.
        $this->assertEquals(array("*" => array("superuser" => 1)), $this->session->__getLoggedInPrivileges());


    }

    /**
     * Check we can authenticate as a super user.
     */
    public function testCanLoginAsRegularAccount() {

        // Attempt a login.
        $this->authenticationService->login("sam@samdavisdesign.co.uk", "password");

        // Check the user
        $loggedInUser = $this->session->__getLoggedInUser();
        $this->assertTrue($loggedInUser instanceof User);

        $this->assertEquals(2, $loggedInUser->getId());
        $this->assertEquals("Sam Davis", $loggedInUser->getName());
        $this->assertEquals("07891 147676", $loggedInUser->getMobileNumber());
        $this->assertEquals("samdavis@gmail.com", $loggedInUser->getBackupEmailAddress());

        $this->assertEquals(1, sizeof($loggedInUser->getRoles()));
        $this->assertEquals(1, $loggedInUser->getRoles()[0]->getAccountId());

        $loggedInAccount = $this->session->__getLoggedInAccount();
        $this->assertTrue($loggedInAccount instanceof AccountSummary);
        $this->assertEquals(1, $loggedInAccount->getId());
        $this->assertEquals("Sam Davis Design", $loggedInAccount->getName());

        // Check logged in privileges
        $this->assertEquals(array("1" => array("administrator" => 1), "5" => array("access" => 1), "9" => array("access" => 1)), $this->session->__getLoggedInPrivileges());


    }


    public function testCanLoginAsUserWithPrescribedActiveAccount() {

        $this->authenticationService->login("james@smartcoasting.org", "password");

        // Check the user
        $loggedInUser = $this->session->__getLoggedInUser();
        $this->assertTrue($loggedInUser instanceof User);

        $this->assertEquals(4, $loggedInUser->getId());

        $this->assertEquals(2, sizeof($loggedInUser->getRoles()));
        $this->assertEquals(2, $loggedInUser->getRoles()[0]->getAccountId());
        $this->assertEquals(3, $loggedInUser->getRoles()[1]->getAccountId());


        $loggedInAccount = $this->session->__getLoggedInAccount();
        $this->assertTrue($loggedInAccount instanceof AccountSummary);
        $this->assertEquals(2, $loggedInAccount->getId());
        $this->assertEquals("Peter Jones Car Washing", $loggedInAccount->getName());

    }


    public function testCanLoginAsSubUserOfParentAccountIfParentAccountContextActive() {


        // Activate parent context.
        $this->authenticationService->updateActiveParentAccount("http://samdavis.org/mark");

        $this->authenticationService->login("james@smartcoasting.org", "password");

        // Check the user
        $loggedInUser = $this->session->__getLoggedInUser();
        $this->assertTrue($loggedInUser instanceof User);

        $this->assertEquals(9, $loggedInUser->getId());

        $this->assertEquals(1, sizeof($loggedInUser->getRoles()));
        $this->assertEquals(5, $loggedInUser->getRoles()[0]->getAccountId());


        $loggedInAccount = $this->session->__getLoggedInAccount();
        $this->assertTrue($loggedInAccount instanceof AccountSummary);
        $this->assertEquals(5, $loggedInAccount->getId());
        $this->assertEquals("Smart Coasting - Design Account", $loggedInAccount->getName());

        // Reset parent context.
        $this->authenticationService->updateActiveParentAccount("https://www.google.com/hello/123");


    }


    public function testExceptionRaisedIfInvalidUsernameOrPasswordSupplied() {

        try {
            $this->authenticationService->login("bobby@wrong.test", "helloworld");
            $this->fail("Should have thrown here");
        } catch (InvalidLoginException $e) {
            // Success
        }

        try {
            $this->authenticationService->login("admin@kinicart.com", "helloworld");
            $this->fail("Should have thrown here");
        } catch (InvalidLoginException $e) {
            // Success
        }

        $this->assertTrue(true);
    }


    public function testExceptionRaisedIfUserIsPendingOrSuspended() {


        try {
            $this->authenticationService->login("suspended@suspendeduser.com", "password");
            $this->fail("Should have thrown here");
        } catch (UserSuspendedException $e) {
            // Success
        }

        try {
            $this->authenticationService->login("pending@myfactoryoutlet.com", "password");
            $this->fail("Should have thrown here");
        } catch (InvalidLoginException $e) {
            // Success
        }


        $this->assertTrue(true);

    }


    public function testIfAccountSuspendedUsersCannotLoginToThatAccount() {

        // Test one where the user is attached to a single account which is suspended.
        try {
            $this->authenticationService->login("john@shoppingonline.com", "password");
            $this->fail("Should have thrown here");
        } catch (AccountSuspendedException $e) {
            // Success
        }


        // now test one with an active account which is suspended.  Check that the active account is set to the alternative account.
        $this->authenticationService->login("mary@shoppingonline.com", "password");

        $loggedInUser = $this->session->__getLoggedInUser();
        $this->assertTrue($loggedInUser instanceof User);
        $this->assertEquals(7, $loggedInUser->getId());

        $loggedInAccount = $this->session->__getLoggedInAccount();
        $this->assertTrue($loggedInAccount instanceof AccountSummary);
        $this->assertEquals(2, $loggedInAccount->getId());


    }


    public function testCanAuthenticateWithAPICredentials() {

        try {
            $this->authenticationService->apiAuthenticate("BADKEY", "BADSECRET");
            $this->fail("Should have thrown here");
        } catch (InvalidAPICredentialsException $e) {
            // Success
        }

        $this->authenticationService->apiAuthenticate("TESTAPIKEY", "TESTAPISECRET");

        $this->assertNull($this->session->__getLoggedInUser());
        $this->assertNotNull($this->session->__getLoggedInAccount());

        $this->assertEquals(1, $this->session->__getLoggedInAccount()->getId());

    }


    public function testCanLogOut() {
        $this->authenticationService->login("james@smartcoasting.org", "password");
        $this->assertNotNull($this->session->__getLoggedInAccount());
        $this->assertNotNull($this->session->__getLoggedInUser());

        $this->authenticationService->logout();
        $this->assertNull($this->session->__getLoggedInAccount());
        $this->assertNull($this->session->__getLoggedInUser());

    }

    public function testSessionReferrerAndParentAccountIsCorrectlyUpdatedWhenCallingUpdateParentAccount() {


        $this->authenticationService->updateActiveParentAccount("https://www.google.com/hello/123");

        $this->assertEquals("www.google.com", $this->session->__getReferringURL());
        $this->assertEquals(0, $this->session->__getActiveParentAccountId());

        $this->authenticationService->updateActiveParentAccount("http://apps.hello.org/mark");

        $this->assertEquals("apps.hello.org", $this->session->__getReferringURL());
        $this->assertEquals(0, $this->session->__getActiveParentAccountId());


        $this->authenticationService->updateActiveParentAccount("http://samdavis.org/mark");

        $this->assertEquals("samdavis.org", $this->session->__getReferringURL());
        $this->assertEquals(1, $this->session->__getActiveParentAccountId());

        $this->authenticationService->updateActiveParentAccount("");

    }

    public function testIfLoggedInAndParentAccountHasChangedOnUpdateUserUserLoggedOutForSecurity() {

        $this->authenticationService->updateActiveParentAccount("https://www.google.com/hello/123");

        $this->authenticationService->login("simon@peterjonescarwash.com", "password");

        $this->assertNotNull($this->session->__getLoggedInUser());
        $this->assertNotNull($this->session->__getLoggedInAccount());

        $this->authenticationService->updateActiveParentAccount("http://samdavis.org/mark");

        $this->assertNull($this->session->__getLoggedInUser());
        $this->assertNull($this->session->__getLoggedInAccount());

        $this->authenticationService->updateActiveParentAccount("");
    }





}
