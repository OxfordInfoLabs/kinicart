<?php

namespace Kinicart\Services\Product;

use Kinicart\Objects\Product\PackagedProduct\Email;
use Kinicart\Objects\Product\PackagedProduct\VirtualHost;
use Kinicart\TestBase;
use Kinikit\Core\DependencyInjection\Container;

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


