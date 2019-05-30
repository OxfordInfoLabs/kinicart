<?php

namespace Kinicart\Test\Objects\Application;

use Kinicart\Exception\Application\InvalidLoginException;
use Kinicart\Exception\Application\UserSuspendedException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\AccountSummary;
use Kinicart\Objects\Account\User;
use Kinicart\Objects\Application\Authenticator;
use Kinicart\Objects\Application\Session;

include_once __DIR__ . "/../../autoloader.php";

class AuthenticatorTest extends \PHPUnit\Framework\TestCase {


    public function testCanCheckWhetherEmailExistsOrNot() {

        $this->assertFalse(Authenticator::instance()->emailExists("james@test.com"));
        $this->assertFalse(Authenticator::instance()->emailExists("bobby@wrong.test"));
        $this->assertTrue(Authenticator::instance()->emailExists("admin@kinicart.com"));

    }


    public function testExceptionRaisedIfInvalidUsernameOrPasswordSupplied() {

        try {
            Authenticator::instance()->logIn("bobby@wrong.test", "helloworld");
            $this->fail("Should have thrown here");
        } catch (InvalidLoginException $e) {
            // Success
        }

        try {
            Authenticator::instance()->logIn("admin@kinicart.com", "helloworld");
            $this->fail("Should have thrown here");
        } catch (InvalidLoginException $e) {
            // Success
        }

        $this->assertTrue(true);
    }


    public function testExceptionRaisedIfUserIsPendingOrSuspended() {


        try {
            Authenticator::instance()->logIn("suspended@suspendeduser.com", "password");
            $this->fail("Should have thrown here");
        } catch (UserSuspendedException $e) {
            // Success
        }

        try {
            Authenticator::instance()->logIn("pending@myfactoryoutlet.com", "password");
            $this->fail("Should have thrown here");
        } catch (InvalidLoginException $e) {
            // Success
        }


        $this->assertTrue(true);

    }


    /**
     * Check we can authenticate as a super user.
     */
    public function testCanLoginAsSuperUser() {

        // Attempt a login.
        Authenticator::instance()->logIn("admin@kinicart.com", "password");

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
        Authenticator::instance()->logIn("sam@samdavisdesign.co.uk", "password");

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

        Authenticator::instance()->logIn("james@smartcoasting.org", "password");

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


    public function testCanLogOut() {
        Authenticator::instance()->logIn("james@smartcoasting.org", "password");
        $this->assertNotNull(Session::instance()->getLoggedInAccount());
        $this->assertNotNull(Session::instance()->getLoggedInUser());

        Authenticator::instance()->logOut();
        $this->assertNull(Session::instance()->getLoggedInAccount());
        $this->assertNull(Session::instance()->getLoggedInUser());

    }

}
