<?php

namespace Kinicart\Test\Objects\Account;

use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\User;
use Kinicart\Test\TestBase;
use Kinikit\Core\Exception\ValidationException;

include_once __DIR__ . "/../../autoloader.php";


class UserTest extends TestBase {


    /**
     * Create a user with a brand new account.
     */
    public function testCanCreateUserWithABrandNewAccount() {

        // Simple one with just email address and password.
        $newUser = User::createWithAccount("john@test.com", "helloworld");

        $this->assertNotNull($newUser->getId());
        $this->assertEquals("john@test.com", $newUser->getEmailAddress());
        $this->assertEquals(hash("md5", "helloworld"), $newUser->getHashedPassword());
        $this->assertEquals(0, $newUser->getParentAccountId());
        $this->assertEquals(User::STATUS_PENDING, $newUser->getStatus());

        $this->assertEquals(1, sizeof($newUser->getRoles()));
        $this->assertEquals(1, sizeof($newUser->getAccounts()));

        $this->assertEquals("john@test.com", $newUser->getActiveAccount()->getName());
        $this->assertEquals(Account::STATUS_ACTIVE, $newUser->getActiveAccount()->getStatus());


        // Now do one with a users name, check propagation to account name.
        // Simple one with just email address and password.
        $newUser = User::createWithAccount("john2@test.com", "helloworld", "John Smith");

        $this->assertNotNull($newUser->getId());
        $this->assertEquals("john2@test.com", $newUser->getEmailAddress());
        $this->assertEquals("John Smith", $newUser->getName());
        $this->assertEquals(hash("md5", "helloworld"), $newUser->getHashedPassword());
        $this->assertEquals(0, $newUser->getParentAccountId());
        $this->assertEquals(User::STATUS_PENDING, $newUser->getStatus());

        $this->assertEquals(1, sizeof($newUser->getRoles()));
        $this->assertEquals(1, sizeof($newUser->getAccounts()));

        $this->assertEquals("John Smith", $newUser->getActiveAccount()->getName());
        $this->assertEquals(Account::STATUS_ACTIVE, $newUser->getActiveAccount()->getStatus());


        // Now do one with a user and account name, check propagation to account name.
        // Simple one with just email address and password.
        $newUser = User::createWithAccount("john3@test.com", "helloworld", "John Smith",
            "Smith Enterprises");

        $this->assertNotNull($newUser->getId());
        $this->assertEquals("john3@test.com", $newUser->getEmailAddress());
        $this->assertEquals("John Smith", $newUser->getName());
        $this->assertEquals(hash("md5", "helloworld"), $newUser->getHashedPassword());
        $this->assertEquals(0, $newUser->getParentAccountId());
        $this->assertEquals(User::STATUS_PENDING, $newUser->getStatus());

        $this->assertEquals(1, sizeof($newUser->getRoles()));
        $this->assertEquals(1, sizeof($newUser->getAccounts()));

        $this->assertEquals("Smith Enterprises", $newUser->getActiveAccount()->getName());
        $this->assertEquals(Account::STATUS_ACTIVE, $newUser->getActiveAccount()->getStatus());


        // Check duplicate issue

        try {
            User::createWithAccount("john3@test.com", "helloworld", "John Smith",
                "Smith Enterprises");

            $this->fail("Should have thrown validation problems here");

        } catch (ValidationException $e) {
            // Success
        }

        // Now do one with a user and account name and parent account id. check propagation to account name.
        // Simple one with just email address and password.
        $newUser = User::createWithAccount("john3@test.com", "helloworld", "John Smith",
            "Smith Enterprises", 1);

        $this->assertNotNull($newUser->getId());
        $this->assertEquals("john3@test.com", $newUser->getEmailAddress());
        $this->assertEquals("John Smith", $newUser->getName());
        $this->assertEquals(hash("md5", "helloworld"), $newUser->getHashedPassword());
        $this->assertEquals(1, $newUser->getParentAccountId());
        $this->assertEquals(User::STATUS_PENDING, $newUser->getStatus());

        $this->assertEquals(1, sizeof($newUser->getRoles()));
        $this->assertEquals(1, sizeof($newUser->getAccounts()));

        $this->assertEquals("Smith Enterprises", $newUser->getActiveAccount()->getName());
        $this->assertEquals(Account::STATUS_ACTIVE, $newUser->getActiveAccount()->getStatus());


    }


}
