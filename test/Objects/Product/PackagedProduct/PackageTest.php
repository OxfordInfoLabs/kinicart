<?php


namespace Kinicart\Objects\Product\PackagedProduct;

use Kinicart\Exception\Pricing\InvalidCurrencyException;
use Kinicart\Exception\Pricing\InvalidTierException;
use Kinicart\Exception\Pricing\MissingProductPriceException;
use Kinicart\Objects\Pricing\ProductBasePrice;
use Kinicart\TestBase;
use Kinicart\WebServices\ValueObjects\Product\PackagedProduct\PackageSummary;

include_once __DIR__ . "/../../../autoloader.php";

class PackageTest extends TestBase {


    public function testCanGetPackagePricingForTierAndCurrencyWhenExplicitPricesSet() {

        /**
         * @var Package $package
         */
        $package = Package::fetch(["virtual-host", "SMALL_BUSINESS"]);

        // Check all explicit prices are returned.
        $this->assertEquals(6.99, $package->getTierPrice(1, ProductBasePrice::RECURRENCE_MONTHLY, "GBP"));
        $this->assertEquals(6.99, $package->getTierPrice(1, ProductBasePrice::RECURRENCE_MONTHLY, "USD"));
        $this->assertEquals(6.99, $package->getTierPrice(1, ProductBasePrice::RECURRENCE_MONTHLY, "EUR"));

        $this->assertEquals(7.99, $package->getTierPrice(2, ProductBasePrice::RECURRENCE_MONTHLY, "GBP"));
        $this->assertEquals(8.99, $package->getTierPrice(3, ProductBasePrice::RECURRENCE_MONTHLY, "GBP"));


        $this->assertEquals("50.00", $package->getTierPrice(1, ProductBasePrice::RECURRENCE_ANNUAL, "GBP"));
        $this->assertEquals("50.00", $package->getTierPrice(1, ProductBasePrice::RECURRENCE_ANNUAL, "USD"));
        $this->assertEquals("50.00", $package->getTierPrice(1, ProductBasePrice::RECURRENCE_ANNUAL, "EUR"));

        $this->assertEquals("60.00", $package->getTierPrice(2, ProductBasePrice::RECURRENCE_ANNUAL, "GBP"));
        $this->assertEquals("70.00", $package->getTierPrice(3, ProductBasePrice::RECURRENCE_ANNUAL, "GBP"));


    }


    public function testCanGetInferredPackagePricingForTierWhereExplicitPriceButInDifferentCurrency() {

        /**
         * @var Package $package
         */
        $package = Package::fetch(["virtual-host", "SMALL_BUSINESS"]);


        $this->assertEquals("72.00", $package->getTierPrice(2, ProductBasePrice::RECURRENCE_ANNUAL, "USD"));
        $this->assertEquals("66.00", $package->getTierPrice(2, ProductBasePrice::RECURRENCE_ANNUAL, "EUR"));

        $this->assertEquals("84.00", $package->getTierPrice(3, ProductBasePrice::RECURRENCE_ANNUAL, "USD"));
        $this->assertEquals("77.00", $package->getTierPrice(3, ProductBasePrice::RECURRENCE_ANNUAL, "EUR"));


    }


    public function testCanGetInferredPricingWhereOnlyBasePricesSupplied() {

        /**
         * @var Package $package
         */
        $package = Package::fetch(["virtual-host", "BUDGET"]);

        // In defined currency first
        $this->assertEquals("3.75", $package->getTierPrice(1, ProductBasePrice::RECURRENCE_MONTHLY, "USD"));
        $this->assertEquals("37.50", $package->getTierPrice(1, ProductBasePrice::RECURRENCE_ANNUAL, "USD"));

        $this->assertEquals("3.30", $package->getTierPrice(2, ProductBasePrice::RECURRENCE_MONTHLY, "USD"));
        $this->assertEquals("33.00", $package->getTierPrice(2, ProductBasePrice::RECURRENCE_ANNUAL, "USD"));

        $this->assertEquals("3.00", $package->getTierPrice(3, ProductBasePrice::RECURRENCE_MONTHLY, "USD"));
        $this->assertEquals("30.00", $package->getTierPrice(3, ProductBasePrice::RECURRENCE_ANNUAL, "USD"));


        // In GBP now (divided by 1.25)
        $this->assertEquals("3.13", $package->getTierPrice(1, ProductBasePrice::RECURRENCE_MONTHLY, "GBP"));
        $this->assertEquals("31.25", $package->getTierPrice(1, ProductBasePrice::RECURRENCE_ANNUAL, "GBP"));

        $this->assertEquals("2.75", $package->getTierPrice(2, ProductBasePrice::RECURRENCE_MONTHLY, "GBP"));
        $this->assertEquals("27.50", $package->getTierPrice(2, ProductBasePrice::RECURRENCE_ANNUAL, "GBP"));

        $this->assertEquals("2.50", $package->getTierPrice(3, ProductBasePrice::RECURRENCE_MONTHLY, "GBP"));
        $this->assertEquals("25.00", $package->getTierPrice(3, ProductBasePrice::RECURRENCE_ANNUAL, "GBP"));


    }


    public function testIfNoPricesSetMissingPriceExceptionRaised() {

        $package = new Package("virtual-host", "BADPRODUCT");
        $package->save();

        /**
         * @var Package $package
         */
        $package = Package::fetch(["virtual-host", "BADPRODUCT"]);

        try {
            $package->getTierPrice(1, ProductBasePrice::RECURRENCE_MONTHLY, "GBP");
            $this->fail("Should have thrown here");
        } catch (MissingProductPriceException $e) {
            // Success
        }

        $this->assertTrue(true);

    }


    public function testIfBadCurrencySuppliedExceptionRaised() {

        /**
         * @var Package $package
         */
        $package = Package::fetch(["virtual-host", "BUDGET"]);

        try {
            $package->getTierPrice(1, ProductBasePrice::RECURRENCE_MONTHLY, "KRR");
            $this->fail("Should have thrown here");
        } catch (InvalidCurrencyException $e) {
            // Success
        }

        $this->assertTrue(true);

    }


    public function testIfBadTierSuppliedExceptionRaised() {


        /**
         * @var Package $package
         */
        $package = Package::fetch(["virtual-host", "BUDGET"]);

        try {
            $package->getTierPrice(5, ProductBasePrice::RECURRENCE_MONTHLY, "GBP");
            $this->fail("Should have thrown here");
        } catch (InvalidTierException $e) {
            // Success
        }

        $this->assertTrue(true);
    }


    public function testCanGetAllTierPricesForCurrency() {

        /**
         * @var Package $package
         */
        $package = Package::fetch(["virtual-host", "BUDGET"]);


        $allPrices = $package->getAllTierPrices("GBP");
        $this->assertEquals(2, sizeof($allPrices));

        // Check sub arrays
        $this->assertEquals(3, sizeof($allPrices["MONTHLY"]));
        $this->assertEquals($package->getTierPrice(1, "MONTHLY", "GBP"), $allPrices["MONTHLY"][1]);
        $this->assertEquals($package->getTierPrice(2, "MONTHLY", "GBP"), $allPrices["MONTHLY"][2]);
        $this->assertEquals($package->getTierPrice(3, "MONTHLY", "GBP"), $allPrices["MONTHLY"][3]);


        // Check sub arrays
        $this->assertEquals(3, sizeof($allPrices["ANNUAL"]));
        $this->assertEquals($package->getTierPrice(1, "ANNUAL", "GBP"), $allPrices["ANNUAL"][1]);
        $this->assertEquals($package->getTierPrice(2, "ANNUAL", "GBP"), $allPrices["ANNUAL"][2]);
        $this->assertEquals($package->getTierPrice(3, "ANNUAL", "GBP"), $allPrices["ANNUAL"][3]);

    }

}