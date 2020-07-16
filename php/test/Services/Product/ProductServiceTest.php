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


    public function setUp(): void {
        parent::setUp();
        $this->service = Container::instance()->get(ProductService::class);
    }

    public function testCanGetPackagedProductByIdentifierBasedOnIncludedPackagedProductsJSON() {

        $packagedProduct = $this->service->getProduct("virtual-host");
        $this->assertEquals(Container::instance()->get(VirtualHost::class), $packagedProduct);

        $packagedProduct = $this->service->getProduct("email");
        $this->assertEquals(Container::instance()->get(Email::class), $packagedProduct);


    }


}


