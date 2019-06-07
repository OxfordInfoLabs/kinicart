<?php


namespace Kinicart\Test\Services\Application;

use Kinicart\Services\Application\AuthenticationService;
use Kinicart\Test\TestBase;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Persistence\UPF\Exception\UPFObjectSaveVetoedException;

include_once __DIR__ . "/../../autoloader.php";

class MethodInterceptorTest extends TestBase {


    /**
     * @var TestMethodService
     */
    private $testMethodService;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;


    public function setUp() {
        parent::setUp();
        $this->testMethodService = Container::instance()->get("\Kinicart\Test\Services\Security\TestMethodService");
        $this->authenticationService = Container::instance()->get("\Kinicart\Services\Security\AuthenticationService");
    }


    public function testObjectInterceptorIsDisabledIfAttributeAddedToMethod() {

        $this->authenticationService->logout();

        try {
            $this->testMethodService->normalMethod();
            $this->fail("Should have thrown here");
        } catch (UPFObjectSaveVetoedException $e) {
            // As expected
        }


        $this->testMethodService->objectInterceptorDisabledMethod();
        $this->assertTrue(true);

    }


}
