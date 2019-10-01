<?php

namespace Kinicart\Services\Product\PackagedProduct;

use Kinicart\Objects\Product\PackagedProduct\Email;
use Kinicart\Objects\Product\PackagedProduct\VirtualHost;
use Kinicart\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once __DIR__ . "/../../../autoloader.php";

class PackagedProductServiceTest extends TestBase {

    /**
     * @var PackagedProductService
     */
    private $service;

    public function setUp(): void {
        parent::setUp();
        $this->service = Container::instance()->get(PackagedProductService::class);
    }

    public function testCanGetPackagedProductByIdentifierBasedOnIncludedPackagedProductsJSON() {

        $packagedProduct = $this->service->getPackagedProduct("virtual-host");
        $this->assertEquals(Container::instance()->get(VirtualHost::class), $packagedProduct);


        $packagedProduct = $this->service->getPackagedProduct("email");
        $this->assertEquals(Container::instance()->get(Email::class), $packagedProduct);


        try {
            $this->service->getPackagedProduct("non-existent");
            $this->fail("Should have thrown here");
        } catch (\Exception $e) {
            // Success
        }

    }

    

}
