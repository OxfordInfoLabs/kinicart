<?php

namespace Kinicart\Services\Product\PackagedProduct;

use Kinicart\Objects\Product\PackagedProduct\Email;
use Kinicart\Objects\Product\PackagedProduct\Feature;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductFeature;
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


    public function testCanGetAllFeaturesForProductAsPackagedProductFeatureObjects() {

        $allFeatures = $this->service->getAllProductFeatures("virtual-host");

        $this->assertEquals(5, sizeof($allFeatures));
        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("memory", "Memory (GB)", "The amount of memory allocated to this VM"),
            0.02, 0.01, "USD"),
            $allFeatures[0]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("diskSpace", "Disk Space (GB)", "The amount of disk space allocated to this VM"),
            0.1, 0.05, "USD"),
            $allFeatures[1]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("includedBandwidth", "Included Bandwidth (GB/month)", "The amount of included bandwidth in GB/Month"),
            0.001, 0.001, "EUR"),
            $allFeatures[2]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("additionalBandwidth", "Excess Bandwidth (GB/month)", "Additional bandwidth per GB")),
            $allFeatures[3]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("excessBandwidth", "Excess Bandwidth (GB/month)", "Excess bandwidth charges - additional GBs", Feature::TYPE_EXCESS)),
            $allFeatures[4]);


    }


}
