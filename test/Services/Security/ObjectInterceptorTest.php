<?php


namespace Kinicart\Test\Services\Application;

use Kinicart\Objects\Account\Contact;
use Kinicart\Test\Services\Security\TestNonAccountObject;
use Kinicart\Test\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once __DIR__ . "/../../autoloader.php";

class ObjectInterceptorTest extends TestBase {

    /**
     * @var \Kinicart\Services\Application\ObjectInterceptor
     */
    private $objectInterceptor;

    /**
     * @var \Kinicart\Services\Application\AuthenticationService
     */
    private $authenticationService;

    public function setUp() {
        parent::setUp();
        $this->objectInterceptor = Container::instance()->get("Kinicart\Services\Security\ObjectInterceptor");
        $this->authenticationService = Container::instance()->get("Kinicart\Services\Security\AuthenticationService");
    }


    public function testAdhocObjectsNotContainingAccountIdAreAllowedThroughAllPreMethods() {

        $adhocObject = new TestNonAccountObject(1, "Marky Mark", "Marky Mark and the funky bunch");
        $this->assertTrue($this->objectInterceptor->preSave($adhocObject));
        $this->assertTrue($this->objectInterceptor->preDelete($adhocObject));
        $this->assertTrue($this->objectInterceptor->preMap("TestNonAccountObject", $adhocObject->__getSerialisablePropertyMap()));

    }


    public function testObjectsWithAccountIdAreCheckedForAccountOwnershipOfLoggedInUser() {


        $contact = new Contact("Mark", "Hello World", "1 This Lane", "This town", "London",
            "London", "LH1 4YY", "GB", null, null, 1);

        // Start logged out and confirm that interceptors fail.
        $this->authenticationService->logOut();

        $this->assertFalse($this->objectInterceptor->preSave($contact));
        $this->assertFalse($this->objectInterceptor->preDelete($contact));
        $this->assertFalse($this->objectInterceptor->postMap($contact));


        // Now log in as a different account and confirm that interceptors fail.
        $this->authenticationService->logIn("simon@peterjonescarwash.com", "password");

        $this->assertFalse($this->objectInterceptor->preSave($contact));
        $this->assertFalse($this->objectInterceptor->preDelete($contact));
        $this->assertFalse($this->objectInterceptor->postMap($contact));


        // Now log in as an account with authority and confirm that interceptors succeed.
        $this->authenticationService->logIn("sam@samdavisdesign.co.uk", "password");

        $this->assertTrue($this->objectInterceptor->preSave($contact));
        $this->assertTrue($this->objectInterceptor->preDelete($contact));
        $this->assertTrue($this->objectInterceptor->postMap($contact));


    }


    public function testCanExecuteABlockInsecurelyWhichWillAlwaysReturnTrueForInterceptors() {

        $contact = new Contact("Mark", "Hello World", "1 This Lane", "This town", "London",
            "London", "LH1 4YY", "GB", null, null, 1);

        // Start logged out.
        $this->authenticationService->logOut();

        // Check that the interceptor is disabled for the duration of this function
        $this->objectInterceptor->executeInsecure(function () use ($contact) {
            $this->assertTrue($this->objectInterceptor->preSave($contact));
            $this->assertTrue($this->objectInterceptor->preDelete($contact));
            $this->assertTrue($this->objectInterceptor->postMap($contact));
        });

        // And re-enabled afterwards.
        $this->assertFalse($this->objectInterceptor->preSave($contact));
        $this->assertFalse($this->objectInterceptor->preDelete($contact));
        $this->assertFalse($this->objectInterceptor->postMap($contact));


        try {

            // Check that an exception raised still resets the interceptor
            $this->objectInterceptor->executeInsecure(function () use ($contact) {
                throw new \Exception("Test Exception");
            });
        } catch (\Exception $e) {
            // Fine
        }

        // And re-enabled afterwards.
        $this->assertFalse($this->objectInterceptor->preSave($contact));
        $this->assertFalse($this->objectInterceptor->preDelete($contact));
        $this->assertFalse($this->objectInterceptor->postMap($contact));


    }


}
