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

        $vhostFeatures = $this->service->getAllProductFeatures("virtual-host");

        $this->assertEquals(5, sizeof($vhostFeatures));
        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("memory", "Memory (GB)", "The amount of memory allocated to this VM"),
            0.02, 0.01, "USD"),
            $vhostFeatures[0]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("diskSpace", "Disk Space (GB)", "The amount of disk space allocated to this VM"),
            0.1, 0.05, "USD"),
            $vhostFeatures[1]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("includedBandwidth", "Included Bandwidth (GB/month)", "The amount of included bandwidth in GB/Month"),
            0.001, 0.001, "EUR"),
            $vhostFeatures[2]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("additionalBandwidth", "Additional Bandwidth (GB/month)", "Additional bandwidth per GB")),
            $vhostFeatures[3]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("excessBandwidth", "Excess Bandwidth (GB/month)", "Excess bandwidth charges - additional GBs", Feature::TYPE_EXCESS)),
            $vhostFeatures[4]);


        $emailFeatures = $this->service->getAllProductFeatures("email");
        $this->assertEquals(7, sizeof($emailFeatures));

        $this->assertEquals(new PackagedProductFeature("email", new Feature("storage", "Storage (GB)", "The amount of storage space included per user"),
            0.2, 0.1, "USD"), $emailFeatures[0]);

        $this->assertEquals(new PackagedProductFeature("email", new Feature("users", "Users", "The number of users currently enabled for email"),
            0, 2, "GBP"), $emailFeatures[1]);

        $this->assertEquals(new PackagedProductFeature("email",
            new Feature("includedBandwidth", "Included Bandwidth (GB/month)", "The amount of included bandwidth in GB/Month")),
            $emailFeatures[2]);


    }


    public function testCanSaveProductFeatures() {

        $vhostFeatures = $this->service->getAllProductFeatures("virtual-host");

        $vhostFeatures[3]->setUnitSupplierBuyPrice(0.002);
        $vhostFeatures[3]->setUnitBaseMargin(0.002);
        $vhostFeatures[3]->setWorkingCurrency("GBP");

        $vhostFeatures[4]->setUnitSupplierBuyPrice(0.1);
        $vhostFeatures[4]->setUnitBaseMargin(0.2);
        $vhostFeatures[4]->setWorkingCurrency("GBP");

        // Save product features.
        $this->service->saveProductFeatures($vhostFeatures);


        $vhostFeatures = $this->service->getAllProductFeatures("virtual-host");

        $this->assertEquals(5, sizeof($vhostFeatures));
        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("memory", "Memory (GB)", "The amount of memory allocated to this VM"),
            0.02, 0.01, "USD"),
            $vhostFeatures[0]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("diskSpace", "Disk Space (GB)", "The amount of disk space allocated to this VM"),
            0.1, 0.05, "USD"),
            $vhostFeatures[1]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("includedBandwidth", "Included Bandwidth (GB/month)", "The amount of included bandwidth in GB/Month"),
            0.001, 0.001, "EUR"),
            $vhostFeatures[2]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("additionalBandwidth", "Additional Bandwidth (GB/month)", "Additional bandwidth per GB"),
            0.002, 0.002, "GBP"),
            $vhostFeatures[3]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("excessBandwidth", "Excess Bandwidth (GB/month)", "Excess bandwidth charges - additional GBs", Feature::TYPE_EXCESS),
            0.1, 0.2, "GBP"),
            $vhostFeatures[4]);

    }


}
