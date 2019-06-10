<?php

namespace Kinicart\Test\WebServices\Security;


use Kinicart\Exception\Security\AccessDeniedException;
use Kinicart\Exception\Security\InvalidAPICredentialsException;
use Kinicart\Exception\Security\MissingAPICredentialsException;
use Kinicart\Services\Security\AuthenticationService;
use Kinicart\Services\Security\SecurityService;
use Kinicart\Test\TestBase;
use Kinicart\WebServices\Security\DefaultControllerAccessInterceptor;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Util\HTTP\HttpRequest;
use Kinikit\Core\Util\HTTP\URLHelper;

include_once __DIR__ . "/../../autoloader.php";

class DefaultControllerAccessInterceptorTest extends TestBase {

    private $authenticationService;

    /**
     * @var DefaultControllerAccessInterceptor
     */
    private $defaultControllerAccessInterceptor;


    /***
     * @var TestController
     */
    private $testController;

    public function setUp() {
        $this->authenticationService = Container::instance()->get(AuthenticationService::class);
        $securityService = Container::instance()->get(SecurityService::class);
        $this->defaultControllerAccessInterceptor = new DefaultControllerAccessInterceptor($securityService, $this->authenticationService);
        $this->testController = new TestController();
    }

    public function testPublicControllerURLsAreAccessibleByAll() {

        URLHelper::setTestURL("/public/somecontroller?mynameistest");

        // Public
        $this->authenticationService->logout();
        $this->defaultControllerAccessInterceptor->afterCreate($this->testController);

        // Account user
        $this->authenticationService->login("simon@peterjonescarwash.com", "password");
        $this->defaultControllerAccessInterceptor->afterCreate($this->testController);

        // Root user
        $this->authenticationService->login("admin@kinicart.com", "password");
        $this->defaultControllerAccessInterceptor->afterCreate($this->testController);


        // API login
        $this->authenticationService->apiAuthenticate("TESTAPIKEY", "TESTAPISECRET");
        $this->defaultControllerAccessInterceptor->afterCreate($this->testController);

        $this->assertTrue(true);

    }


    public function testCustomerControllerURLsAreNotAccessibleByPublic() {

        URLHelper::setTestURL("/customer/somecontroller?mynameistest");

        // Public
        $this->authenticationService->logout();

        try {
            $this->defaultControllerAccessInterceptor->afterCreate($this->testController);
            $this->fail("Should have thrown here");
        } catch (AccessDeniedException $e) {
            // Success
        }

        // Account user
        $this->authenticationService->login("simon@peterjonescarwash.com", "password");
        $this->defaultControllerAccessInterceptor->afterCreate($this->testController);

        // Root user
        $this->authenticationService->login("admin@kinicart.com", "password");
        $this->defaultControllerAccessInterceptor->afterCreate($this->testController);


        // API login
        $this->authenticationService->apiAuthenticate("TESTAPIKEY", "TESTAPISECRET");
        $this->defaultControllerAccessInterceptor->afterCreate($this->testController);

        $this->assertTrue(true);

    }


    public function testAdminControllerURLsAreNotAccessibleByNonSuperusers() {

        URLHelper::setTestURL("/admin/somecontroller?mynameistest");

        // Public
        $this->authenticationService->logout();

        try {
            $this->defaultControllerAccessInterceptor->afterCreate($this->testController);
            $this->fail("Should have thrown here");
        } catch (AccessDeniedException $e) {
            // Success
        }

        // Account user
        $this->authenticationService->login("simon@peterjonescarwash.com", "password");
        try {
            $this->defaultControllerAccessInterceptor->afterCreate($this->testController);
            $this->fail("Should have thrown here");
        } catch (AccessDeniedException $e) {
            // Success
        }


        // Root user
        $this->authenticationService->login("admin@kinicart.com", "password");
        $this->defaultControllerAccessInterceptor->afterCreate($this->testController);


        // API login
        $this->authenticationService->apiAuthenticate("TESTAPIKEY", "TESTAPISECRET");
        try {
            $this->defaultControllerAccessInterceptor->afterCreate($this->testController);
            $this->fail("Should have thrown here");
        } catch (AccessDeniedException $e) {
            // Success
        }


        $this->assertTrue(true);

    }


    public function testAPIControllerURLsAreOnlyAccessibleByLoggedInAccountAndRequireAPIKeyAndSecretForEachRequest() {

        URLHelper::setTestURL("/api/somecontroller?mynameistest");

        // Public
        $this->authenticationService->logout();

        try {
            $this->defaultControllerAccessInterceptor->afterCreate($this->testController);
            $this->fail("Should have thrown here");
        } catch (MissingAPICredentialsException $e) {
            // Success
        }

        // Account user
        $this->authenticationService->login("simon@peterjonescarwash.com", "password");
        try {
            $this->defaultControllerAccessInterceptor->afterCreate($this->testController);
            $this->fail("Should have thrown here");
        } catch (MissingAPICredentialsException $e) {
            // Success
        }


        // Root user
        $this->authenticationService->login("admin@kinicart.com", "password");
        try {
            $this->defaultControllerAccessInterceptor->afterCreate($this->testController);
            $this->fail("Should have thrown here");
        } catch (MissingAPICredentialsException $e) {
            // Success
        }


        // API login first - this should still fail.
        $this->authenticationService->apiAuthenticate("TESTAPIKEY", "TESTAPISECRET");

        try {
            $this->defaultControllerAccessInterceptor->afterCreate($this->testController);
            $this->fail("should have thrown here");
        } catch (MissingAPICredentialsException $e) {
            // Success
        }


        // Now tweak the URL to include bad credentials
        $this->authenticationService->logout();

        URLHelper::setTestURL("/api/somecontroller?mynameistest?apiKey=BADKEY&apiSecret=BADSECRET");
        $_GET = array("apiKey" => "BADKEY", "apiSecret" => "BADSECRET");
        HttpRequest::instance(true);

        try {
            $this->defaultControllerAccessInterceptor->afterCreate($this->testController);
            $this->fail("should have thrown here");
        } catch (InvalidAPICredentialsException $e) {
            // Success
        }


        // Finally good credentials
        URLHelper::setTestURL("/api/somecontroller?mynameistest?apiKey=TESTAPIKEY&apiSecret=TESTAPISECRET");
        $_GET = array("apiKey" => "TESTAPIKEY", "apiSecret" => "TESTAPISECRET");
        HttpRequest::instance(true);

        $this->defaultControllerAccessInterceptor->afterCreate($this->testController);


        $this->assertTrue(true);

    }


}
