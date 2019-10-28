<?php

namespace Kinicart\Services\Product;


use Kiniauth\Objects\Workflow\PendingAction;
use Kiniauth\Services\Workflow\PendingActionService;
use Kinicart\Services\Product\PackagedProduct\Email;
use Kinicart\Services\Product\PackagedProduct\VirtualHost;
use Kinicart\TestBase;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Exception\ItemNotFoundException;
use Kinikit\Core\Testing\MockObject;
use Kinikit\Core\Testing\MockObjectProvider;

include_once __DIR__ . "/../../autoloader.php";

class ProductServiceTest extends TestBase {

    /**
     * @var ProductService
     */
    private $service;

    /**
     * @var PendingActionService
     */
    private $pendingActionService;

    public function setUp(): void {
        parent::setUp();
        $this->service = Container::instance()->get(ProductService::class);
        $this->pendingActionService = Container::instance()->get(PendingActionService::class);
    }

    public function testCanGetPackagedProductByIdentifierBasedOnIncludedPackagedProductsJSON() {

        $packagedProduct = $this->service->getProduct("virtual-host");
        $this->assertEquals(Container::instance()->get(VirtualHost::class), $packagedProduct);

        $packagedProduct = $this->service->getProduct("email");
        $this->assertEquals(Container::instance()->get(Email::class), $packagedProduct);


    }


    public function testCanCreateDeferredProductActionAndPendingActionCreatedWithAppropriateValues() {

        $this->service->createDeferredProductAction("virtual-host", 22, ["test" => "hello"]);

        $pendingActions = $this->pendingActionService->getAllPendingActionsForTypeAndObjectId("PRODUCT_DEFERRED", 22, "virtual-host");
        $this->assertEquals(1, sizeof($pendingActions));

        /**
         * @var $pendingAction PendingAction
         */
        $pendingAction = $pendingActions[0];

        $this->assertEquals(22, $pendingAction->getObjectId());
        $this->assertEquals(["test" => "hello"], $pendingAction->getData());
        $this->assertEquals("PRODUCT_DEFERRED", $pendingAction->getType());
        $this->assertEquals("virtual-host", $pendingAction->getObjectType());
        $this->assertEquals((new \DateTime())->add(new \DateInterval("P1D"))->format("d/m/Y"), $pendingAction->getExpiryDateTime()->format("d/m/Y"));

    }


    public function testCanRunAllProductDeferredActions() {

        /**
         * @var MockObjectProvider $mockObjectProvider
         */
        $mockObjectProvider = Container::instance()->get(MockObjectProvider::class);

        /**
         * @var MockObject
         */
        $mockProduct = $mockObjectProvider->getMockInstance(Product::class);
        Container::instance()->set("TestProduct", $mockProduct);
        $this->service->addProduct("test", "TestProduct");

        $identifier1 = $this->service->createDeferredProductAction("test", 22, ["test" => "hello"]);
        $identifier2 = $this->service->createDeferredProductAction("test", 33, []);


        $this->service->processAllDeferredProductActions();

        $this->assertTrue($mockProduct->methodWasCalled("processDeferredAction", [22, ["test" => "hello"]]));
        $this->assertTrue($mockProduct->methodWasCalled("processDeferredAction", [33, []]));

        // Check that actions still exist.
        $this->pendingActionService->getPendingActionByIdentifier("PRODUCT_DEFERRED", $identifier1);
        $this->pendingActionService->getPendingActionByIdentifier("PRODUCT_DEFERRED", $identifier2);

        // Check that action removed if successful
        $mockProduct->returnValue("processDeferredAction", true, [22, ["test" => "hello"]]);

        $this->service->processAllDeferredProductActions();

        try {
            $this->pendingActionService->getPendingActionByIdentifier("PRODUCT_DEFERRED", $identifier1);
            $this->fail("Should have thrown");
        } catch (ItemNotFoundException $e) {
            // Success
        }
        $this->pendingActionService->getPendingActionByIdentifier("PRODUCT_DEFERRED", $identifier2);


    }

    public function testDeferredActionsForBadProductsAreRemovedImmediately() {

        $identifier1 = $this->service->createDeferredProductAction("badproduct", 22, ["test" => "hello"]);

        $this->service->processAllDeferredProductActions();

        try {
            $this->pendingActionService->getPendingActionByIdentifier("PRODUCT_DEFERRED", $identifier1);
            $this->fail("Should have thrown");
        } catch (ItemNotFoundException $e) {
            // Success
        }

        $this->assertTrue(true);

    }


}


