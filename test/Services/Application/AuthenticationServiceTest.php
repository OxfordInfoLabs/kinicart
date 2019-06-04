<?php

namespace Kinicart\Test\Objects\Application;

use Kinicart\Exception\Application\AccountSuspendedException;
use Kinicart\Exception\Application\InvalidAPICredentialsException;
use Kinicart\Exception\Application\InvalidLoginException;
use Kinicart\Exception\Application\UserSuspendedException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\AccountSummary;
use Kinicart\Objects\Account\User;
use Kinicart\Services\Application\AuthenticationService;
use Kinicart\Objects\Application\BootstrapService;
use Kinicart\Objects\Application\Session;
use Kinicart\Test\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once __DIR__ . "/../../autoloader.php";

class AuthenticationServiceTest extends TestBase {


    private $authenticationService;
    private $bootstrapService;


    public function setUp() {
        $this->authenticationService = Container::instance()->createInstance("Kinicart\Services\Application\AuthenticationService");
        $this->bootstrapService = Container::instance()->createInstance("Kinicart\Services\Application\BootstrapService");
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
        $this->authenticationService->logIn("admin@kinicart.com", "password");

        // Confirm that we are now logged in
        $this->assertNull(Session::instance()->getLoggedInAccount());

        $loggedInUser = Session::instance()->getLoggedInUser();
        $this->assertTrue($loggedInUser instanceof User);

        $this->assertEquals(1, $loggedInUser->getId());
        $this->assertEquals("Administrator", $loggedInUser->getName());
        $this->assertEquals(1, sizeof($loggedInUser->getRoles()));

    }

    /**
     * Check we can authenticate as a super user.
     */
    public function testCanLoginAsRegularAccount() {

        // Attempt a login.
        $this->authenticationService->logIn("sam@samdavisdesign.co.uk", "password");

        // Check the user
        $loggedInUser = Session::instance()->getLoggedInUser();
        $this->assertTrue($loggedInUser instanceof User);

        $this->assertEquals(2, $loggedInUser->getId());
        $this->assertEquals("Sam Davis", $loggedInUser->getName());
        $this->assertEquals("07891 147676", $loggedInUser->getMobileNumber());
        $this->assertEquals("samdavis@gmail.com", $loggedInUser->getBackupEmailAddress());

        $this->assertEquals(1, sizeof($loggedInUser->getRoles()));
        $this->assertEquals(1, $loggedInUser->getRoles()[0]->getAccountId());

        $loggedInAccount = Session::instance()->getLoggedInAccount();
        $this->assertTrue($loggedInAccount instanceof AccountSummary);
        $this->assertEquals(1, $loggedInAccount->getId());
        $this->assertEquals("Sam Davis Design", $loggedInAccount->getName());

    }


    public function testCanLoginAsUserWithPrescribedActiveAccount() {

        $this->authenticationService->logIn("james@smartcoasting.org", "password");

        // Check the user
        $loggedInUser = Session::instance()->getLoggedInUser();
        $this->assertTrue($loggedInUser instanceof User);

        $this->assertEquals(4, $loggedInUser->getId());

        $this->assertEquals(2, sizeof($loggedInUser->getRoles()));
        $this->assertEquals(2, $loggedInUser->getRoles()[0]->getAccountId());
        $this->assertEquals(3, $loggedInUser->getRoles()[1]->getAccountId());


        $loggedInAccount = Session::instance()->getLoggedInAccount();
        $this->assertTrue($loggedInAccount instanceof AccountSummary);
        $this->assertEquals(3, $loggedInAccount->getId());
        $this->assertEquals("Smart Coasting", $loggedInAccount->getName());

    }


    public function testCanLoginAsSubUserOfParentAccountIfParentAccountContextActive() {


        // Activate parent context.
        $_SERVER["HTTP_REFERER"] = "http://samdavis.org/mark";
        $this->bootstrapService->run();

        $this->authenticationService->logIn("james@smartcoasting.org", "password");

        // Check the user
        $loggedInUser = Session::instance()->getLoggedInUser();
        $this->assertTrue($loggedInUser instanceof User);

        $this->assertEquals(9, $loggedInUser->getId());

        $this->assertEquals(1, sizeof($loggedInUser->getRoles()));
        $this->assertEquals(5, $loggedInUser->getRoles()[0]->getAccountId());


        $loggedInAccount = Session::instance()->getLoggedInAccount();
        $this->assertTrue($loggedInAccount instanceof AccountSummary);
        $this->assertEquals(5, $loggedInAccount->getId());
        $this->assertEquals("Smart Coasting - Design Account", $loggedInAccount->getName());

        // Reset parent context.
        $_SERVER["HTTP_REFERER"] = "https://www.google.com/hello/123";
        $this->bootstrapService->run();


    }


    public function testExceptionRaisedIfInvalidUsernameOrPasswordSupplied() {

        try {
            $this->authenticationService->logIn("bobby@wrong.test", "helloworld");
            $this->fail("Should have thrown here");
        } catch (InvalidLoginException $e) {
            // Success
        }

        try {
            $this->authenticationService->logIn("admin@kinicart.com", "helloworld");
            $this->fail("Should have thrown here");
        } catch (InvalidLoginException $e) {
            // Success
        }

        $this->assertTrue(true);
    }


    public function testExceptionRaisedIfUserIsPendingOrSuspended() {


        try {
            $this->authenticationService->logIn("suspended@suspendeduser.com", "password");
            $this->fail("Should have thrown here");
        } catch (UserSuspendedException $e) {
            // Success
        }

        try {
            $this->authenticationService->logIn("pending@myfactoryoutlet.com", "password");
            $this->fail("Should have thrown here");
        } catch (InvalidLoginException $e) {
            // Success
        }


        $this->assertTrue(true);

    }


    public function testIfAccountSuspendedUsersCannotLoginToThatAccount() {

        // Test one where the user is attached to a single account which is suspended.
        try {
            $this->authenticationService->logIn("john@shoppingonline.com", "password");
            $this->fail("Should have thrown here");
        } catch (AccountSuspendedException $e) {
            // Success
        }


        // now test one with an active account which is suspended.  Check that the active account is set to the alternative account.
        $this->authenticationService->logIn("mary@shoppingonline.com", "password");

        $loggedInUser = Session::instance()->getLoggedInUser();
        $this->assertTrue($loggedInUser instanceof User);
        $this->assertEquals(7, $loggedInUser->getId());

        $loggedInAccount = Session::instance()->getLoggedInAccount();
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

        $this->assertNull(Session::instance()->getLoggedInUser());
        $this->assertNotNull(Session::instance()->getLoggedInAccount());

        $this->assertEquals(1, Session::instance()->getLoggedInAccount()->getId());

    }


    public function testCanLogOut() {
        $this->authenticationService->logIn("james@smartcoasting.org", "password");
        $this->assertNotNull(Session::instance()->getLoggedInAccount());
        $this->assertNotNull(Session::instance()->getLoggedInUser());

        $this->authenticationService->logOut();
        $this->assertNull(Session::instance()->getLoggedInAccount());
        $this->assertNull(Session::instance()->getLoggedInUser());

    }


}
