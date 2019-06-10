<?php


namespace Kinicart\Test\Services\Application;

use Kinicart\Exception\Security\AccessDeniedException;
use Kinicart\Services\Application\Session;
use Kinicart\Services\Security\AuthenticationService;
use Kinicart\Test\Services\Security\TestMethodService;
use Kinicart\Test\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once __DIR__ . "/../../autoloader.php";

class MethodInterceptorTest extends TestBase {


    /**
     * @var TestMethodService
     */
    private $testMethodService;

    /**
     * @var AuthenticationService\
     */
    private $authenticationService;


    public function setUp() {
        parent::setUp();
        $this->testMethodService = Container::instance()->get(TestMethodService::class);
        $this->authenticationService = Container::instance()->get(AuthenticationService::class);
    }


    public function testObjectInterceptorIsDisabledIfAttributeAddedToMethod() {

        $this->authenticationService->logout();

        try {
            $this->testMethodService->normalMethod();
            $this->fail("Should have thrown here");
        } catch (AccessDeniedException $e) {
            // As expected
        }


        $this->testMethodService->objectInterceptorDisabledMethod();
        $this->assertTrue(true);

    }


    // Check that access is denied for an exception raised for a method with has privileges.
    public function testAccessDeniedExceptionRaisedForMethodWithHasPrivilegesDefined() {

        $this->authenticationService->logout();

        try {
            $this->testMethodService->accountPermissionRestricted();
            $this->fail("Should have thrown here");
        } catch (AccessDeniedException $e) {
            // Success
        }

        // Now try logging in as a user without the delete data privilege
        $this->authenticationService->login("regularuser@smartcoasting.org", "password");

        try {
            $this->testMethodService->accountPermissionRestricted();
            $this->fail("Should have thrown here");
        } catch (AccessDeniedException $e) {
            // Success
        }

        // Now try a user with delete data privilege
        $this->authenticationService->login("mary@shoppingonline.com", "password");
        $this->assertEquals("OK", $this->testMethodService->accountPermissionRestricted());

        // Now try logging in as an administrator
        $this->authenticationService->login("james@smartcoasting.org", "password");
        $this->assertEquals("OK", $this->testMethodService->accountPermissionRestricted());


        $this->authenticationService->logout();

        try {
            $this->testMethodService->otherAccountPermissionRestricted(1, "marko");
            $this->fail("Should have thrown here");
        } catch (AccessDeniedException $e) {
            // Success
        }

        // Now try logging in as an administrator
        $this->authenticationService->login("james@smartcoasting.org", "password");
        $this->assertEquals("DONE", $this->testMethodService->otherAccountPermissionRestricted(2, "Heydude"));
        $this->assertEquals("DONE", $this->testMethodService->otherAccountPermissionRestricted(3, "Heydude"));

        try {
            $this->testMethodService->otherAccountPermissionRestricted(4, "marko");
            $this->fail("Should have thrown here");
        } catch (AccessDeniedException $e) {
            // Success
        }

    }


    public function testCanInjectLoggedInAccountIdAsDefaultValueViaConstant() {

        $this->authenticationService->logout();
        $this->assertEquals(array("Mark", null), $this->testMethodService->loggedInAccountInjection("Mark"));

        // Now try logging in as a user without the delete data privilege
        $this->authenticationService->login("regularuser@smartcoasting.org", "password");
        $this->assertEquals(array("Mark", 1), $this->testMethodService->loggedInAccountInjection("Mark"));

    }


}
