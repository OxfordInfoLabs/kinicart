<?php

namespace Kinicart\Services\Product\PackagedProduct;

use Kinicart\Objects\Product\PackagedProduct\Feature;
use Kinicart\Objects\Product\PackagedProduct\Package;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductCartItem;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductFeature;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductSubscriptionPackage;
use Kinicart\Objects\Product\PackagedProduct\PackageFeature;
use Kinicart\TestBase;
use Kinicart\ValueObjects\Product\PackagedProduct\PackagedProductCartItemDescriptor;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;
use Kinikit\Persistence\ORM\ORM;

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
            0.02, 0.01, "MONTHLY", "USD"),
            $vhostFeatures[0]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("diskSpace", "Disk Space (GB)", "The amount of disk space allocated to this VM"),
            0.1, 0.05, "MONTHLY", "USD"),
            $vhostFeatures[1]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("includedBandwidth", "Included Bandwidth (GB/month)", "The amount of included bandwidth in GB/Month"),
            0.001, 0.001, "MONTHLY", "EUR"),
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
            0.2, 0.1, "MONTHLY", "USD"), $emailFeatures[0]);

        $this->assertEquals(new PackagedProductFeature("email", new Feature("users", "Users", "The number of users currently enabled for email"),
            0, 2, "MONTHLY", "GBP"), $emailFeatures[1]);

        $this->assertEquals(new PackagedProductFeature("email",
            new Feature("includedBandwidth", "Included Bandwidth (GB/month)", "The amount of included bandwidth in GB/Month")),
            $emailFeatures[2]);


    }


    public function testCanSaveProductFeatures() {

        $vhostFeatures = $this->service->getAllProductFeatures("virtual-host");

        $vhostFeatures[3]->setUnitSupplierBuyPrice(0.002);
        $vhostFeatures[3]->setUnitBaseMargin(0.002);
        $vhostFeatures[3]->setWorkingCurrency("GBP");
        $vhostFeatures[3]->setWorkingPeriod("MONTHLY");

        $vhostFeatures[4]->setUnitSupplierBuyPrice(0.1);
        $vhostFeatures[4]->setUnitBaseMargin(0.2);
        $vhostFeatures[4]->setWorkingCurrency("GBP");
        $vhostFeatures[4]->setWorkingPeriod("ANNUAL");

        // Save product features.
        $this->service->saveProductFeatures($vhostFeatures);


        $vhostFeatures = $this->service->getAllProductFeatures("virtual-host");

        $this->assertEquals(5, sizeof($vhostFeatures));
        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("memory", "Memory (GB)", "The amount of memory allocated to this VM"),
            0.02, 0.01, "MONTHLY", "USD"),
            $vhostFeatures[0]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("diskSpace", "Disk Space (GB)", "The amount of disk space allocated to this VM"),
            0.1, 0.05, "MONTHLY", "USD"),
            $vhostFeatures[1]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("includedBandwidth", "Included Bandwidth (GB/month)", "The amount of included bandwidth in GB/Month"),
            0.001, 0.001, "MONTHLY", "EUR"),
            $vhostFeatures[2]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("additionalBandwidth", "Additional Bandwidth (GB/month)", "Additional bandwidth per GB"),
            0.002, 0.002, "MONTHLY", "GBP"),
            $vhostFeatures[3]);

        $this->assertEquals(new PackagedProductFeature("virtual-host",
            new Feature("excessBandwidth", "Excess Bandwidth (GB/month)", "Excess bandwidth charges - additional GBs", Feature::TYPE_EXCESS),
            0.1, 0.2, "ANNUAL", "GBP"),
            $vhostFeatures[4]);

    }


    public function testCanGetSinglePackageByIdentifier() {

        $plan = $this->service->getPackage("virtual-host", "BUDGET");
        $this->assertEquals(Package::fetch(["virtual-host", "BUDGET"]), $plan);

        $this->assertEquals(Package::TYPE_PLAN, $plan->getType());
        $this->assertEquals("Budget Server", $plan->getTitle());
        $this->assertEquals("Rock bottom budget server - lean and mean", $plan->getDescription());
        $this->assertEquals(4, sizeof($plan->getFeatures()));
        $this->assertEquals(1, sizeof($plan->getChildPackages()));
        $this->assertEquals(Package::fetch(["virtual-host", "BUDGET_5GB"]), $plan->getChildPackages()[0]);

        // Check that each nested feature has a feature object attached to it.
        $feature = $plan->getFeatures()[0];
        $this->assertEquals("memory", $feature->getFeatureIdentifier());
        $this->assertEquals(0.5, $feature->getQuantity());
        $this->assertEquals(PackagedProductFeature::fetch(["virtual-host", "memory"]), $feature->getProductFeature());

        $plan = $this->service->getPackage("virtual-host", "ENTERPRISE");
        $this->assertEquals(Package::fetch(["virtual-host", "ENTERPRISE"]), $plan);


        // Check we can get nested packages
        $addOn = $this->service->getPackage("virtual-host", "BUDGET_5GB");
        $this->assertEquals(Package::fetch(["virtual-host", "BUDGET_5GB"]), $addOn);


        // Check we can get global add ons
        $addOn = $this->service->getPackage("virtual-host", "ACCOUNT_MANAGER");
        $this->assertEquals(Package::fetch(["virtual-host", "ACCOUNT_MANAGER"]), $addOn);

        $this->assertEquals("ADD_ON", $addOn->getType());
        $this->assertEquals("Account Manager", $addOn->getTitle());
        $this->assertEquals("Dedicated Account Manager", $addOn->getDescription());

    }


    public function testCanGetAllPlansForProduct() {

        $plans = $this->service->getAllPlans("virtual-host");
        $expected = [
            Package::fetch(["virtual-host", "BUDGET"]),
            Package::fetch(["virtual-host", "SMALL_BUSINESS"]),
            Package::fetch(["virtual-host", "ENTERPRISE"])
        ];

        $this->assertEquals($expected, $plans);

    }

    public function testCanGetAllAddOnsForProduct() {

        $addOns = $this->service->getAllGlobalAddOns("virtual-host");
        $this->assertEquals([Package::fetch(["virtual-host", "ACCOUNT_MANAGER"])], $addOns);

    }


    public function testCanSavePackages() {

        $packages = $this->service->getAllPlans("virtual-host");

        $packages[0]->setTitle("Biggles");

        $features = $packages[0]->getFeatures();
        $features[] = new PackageFeature(null, "My Favourite Feature", "Fav Man", 15);
        $packages[0]->setFeatures($features);

        $this->service->savePackages($packages);


        $rePackages = $this->service->getAllPlans("virtual-host");
        $this->assertEquals("Biggles", $rePackages[0]->getTitle());
        $this->assertEquals(5, sizeof($rePackages[0]->getFeatures()));
        $this->assertEquals("My Favourite Feature", $rePackages[0]->getFeatures()[4]->getTitle());
        $this->assertEquals("Fav Man", $rePackages[0]->getFeatures()[4]->getDescription());
        $this->assertEquals(15, $rePackages[0]->getFeatures()[4]->getQuantity());


    }


    public function testCanDeletePlansOrAddOns() {

        $this->service->deletePackage("virtual-host", "SMALL_BUSINESS");

        $plans = $this->service->getAllPlans("virtual-host");
        $expected = [
            Package::fetch(["virtual-host", "BUDGET"]),
            Package::fetch(["virtual-host", "ENTERPRISE"])
        ];

        $this->assertEquals($expected, $plans);


        $this->service->deletePackage("virtual-host", "ACCOUNT_MANAGER");

        $this->assertEquals([], $this->service->getAllGlobalAddOns("virtual-host"));

    }


    public function testCanSaveSubscriptionPackagesUsingSubscriptionIdAndPackagedProductCartItem() {

        $cartItem = new PackagedProductCartItem("virtual-host", new PackagedProductCartItemDescriptor("BUDGET"));

        $this->service->saveSubscriptionPackages(10, $cartItem);

        // Check we have what we expect
        $entries = PackagedProductSubscriptionPackage::filter("WHERE subscriptionId = 10");
        $this->assertEquals(1, sizeof($entries));
        $this->assertEquals($this->service->getPackage("virtual-host", "BUDGET"), $entries[0]->getPackage());


        $cartItem = new PackagedProductCartItem("virtual-host", new PackagedProductCartItemDescriptor("BUDGET", ["BUDGET_5GB"]));

        $this->service->saveSubscriptionPackages(10, $cartItem);

        $entries = PackagedProductSubscriptionPackage::filter("WHERE subscriptionId = 10");
        $this->assertEquals(2, sizeof($entries));
        $this->assertEquals($this->service->getPackage("virtual-host", "BUDGET"), $entries[0]->getPackage());
        $this->assertEquals($this->service->getPackage("virtual-host", "BUDGET_5GB"), $entries[1]->getPackage());


        $cartItem = new PackagedProductCartItem("virtual-host", new PackagedProductCartItemDescriptor("ENTERPRISE"));

        $this->service->saveSubscriptionPackages(10, $cartItem);

        // Check we have what we expect
        $entries = PackagedProductSubscriptionPackage::filter("WHERE subscriptionId = 10");
        $this->assertEquals(1, sizeof($entries));
        $this->assertEquals($this->service->getPackage("virtual-host", "ENTERPRISE"), $entries[0]->getPackage());



    }

}
