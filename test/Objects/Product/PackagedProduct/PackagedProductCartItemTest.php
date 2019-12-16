<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinicart\Exception\Product\PackagedProduct\CartItemAddOnDoesNotExistException;
use Kinicart\Exception\Product\PackagedProduct\InvalidCartAddOnException;
use Kinicart\Exception\Product\PackagedProduct\NoSuchProductPlanException;
use Kinicart\Objects\Pricing\ProductBasePrice;
use Kinicart\Services\Product\PackagedProduct\PackagedProductService;
use Kinicart\TestBase;
use Kinicart\Types\Recurrence;
use Kinikit\Core\DependencyInjection\Container;


include_once __DIR__ . "/../../../autoloader.php";

class PackagedProductCartItemTest extends TestBase {


    /**
     * @var PackagedProductService
     */
    private $service;


    /**
     * Set up
     */
    public function setUp(): void {
        $this->service = Container::instance()->get(PackagedProductService::class);
    }


    public function testTitleIsGarneredFromTheProduct() {

        // Create cart item
        $cartItem = new PackagedProductCartItem("virtual-host");
        $this->assertEquals("Virtual Host", $cartItem->getTitle());


    }

    public function testCanCreateCartItemAndSetPlanProvidedItIsValid() {

        // Create cart item
        $cartItem = new PackagedProductCartItem("virtual-host");


        // Invalid
        try {
            $cartItem->setPlan("NON-EXISTENT");
            $this->fail("Should have thrown here");
        } catch (NoSuchProductPlanException $e) {
            // Success
        }

        // Wrong type of package
        try {
            $cartItem->setPlan("ACCOUNT_MANAGER");
            $this->fail("Should have thrown here");
        } catch (NoSuchProductPlanException $e) {
            // Success
        }


        // Test can set valid plan
        $cartItem->setPlan("BUDGET");

        $planProduct = $this->service->getPackage("virtual-host", "BUDGET");

        $this->assertEquals([new PackageCartItem($planProduct)], $cartItem->getSubItems());


    }


    public function testCanAddAddOnsToCartItemProvidedTheyAreValidForProductAndPlan() {


        // Create cart item
        $cartItem = new PackagedProductCartItem("virtual-host");


        // Invalid
        try {
            $cartItem->addAddOn("NON-EXISTENT");
            $this->fail("Should have thrown here");
        } catch (InvalidCartAddOnException $e) {
            // Success
        }

        // Valid but no plan set
        try {
            $cartItem->addAddOn("ACCOUNT_MANAGER");
            $this->fail("Should have thrown here");
        } catch (InvalidCartAddOnException $e) {
            // Success
        }

        // Valid but no plan set
        try {
            $cartItem->addAddOn("BUDGET_5GB");
            $this->fail("Should have thrown here");
        } catch (InvalidCartAddOnException $e) {
            // Success
        }

        // Wrong type
        try {
            $cartItem->addAddOn("ENTERPRISE");
            $this->fail("Should have thrown here");
        } catch (InvalidCartAddOnException $e) {
            // Success
        }


        // Set the plan
        $cartItem->setPlan("BUDGET");

        // Invalid
        try {
            $cartItem->addAddOn("NON-EXISTENT");
            $this->fail("Should have thrown here");
        } catch (InvalidCartAddOnException $e) {
            // Success
        }

        // Wrong plan add-on
        try {
            $cartItem->addAddOn("SMALL_BUSINESS_10GB");
            $this->fail("Should have thrown here");
        } catch (InvalidCartAddOnException $e) {
            // Success
        }

        // Wrong type
        try {
            $cartItem->addAddOn("ENTERPRISE");
            $this->fail("Should have thrown here");
        } catch (InvalidCartAddOnException $e) {
            // Success
        }

        // These should now succeed
        $cartItem->addAddOn("ACCOUNT_MANAGER");
        $cartItem->addAddOn("BUDGET_5GB");


        $planProduct = $this->service->getPackage("virtual-host", "BUDGET");
        $accountManager = $this->service->getPackage("virtual-host", "ACCOUNT_MANAGER");
        $budget5GB = $this->service->getPackage("virtual-host", "BUDGET_5GB");


        $this->assertEquals([new PackageCartItem($planProduct), new PackageCartItem($accountManager), new PackageCartItem($budget5GB)], $cartItem->getSubItems());


    }


    public function testCanUpdatePlanAndDoingSoRemovesAnyInvalidAddOns() {

        // Create cart item
        $cartItem = new PackagedProductCartItem("virtual-host");

        $cartItem->setPlan("BUDGET");
        $cartItem->addAddOn("BUDGET_5GB");
        $cartItem->addAddOn("ACCOUNT_MANAGER");


        // Now update plan
        $cartItem->setPlan("ENTERPRISE");

        // Check add ons.
        $this->assertEquals($this->service->getPackage("virtual-host", "ENTERPRISE"), $cartItem->getPlan());
        $this->assertEquals([$this->service->getPackage("virtual-host", "ACCOUNT_MANAGER")], $cartItem->getAddOns());


    }


    public function testCanRemoveAddOnAtIndexProvidedItExists() {

        // Create cart item
        $cartItem = new PackagedProductCartItem("virtual-host");

        $cartItem->setPlan("BUDGET");
        $cartItem->addAddOn("BUDGET_5GB");
        $cartItem->addAddOn("ACCOUNT_MANAGER");

        try {
            $cartItem->removeAddOn(3);
            $this->fail("Should have thrown here");
        } catch (CartItemAddOnDoesNotExistException $e) {
        }

        $cartItem->removeAddOn(1);

        $this->assertEquals([$this->service->getPackage("virtual-host", "BUDGET_5GB")], $cartItem->getAddOns());


    }


    public function testUnitPriceIsCalculatedAsSumOfPackageComponents() {


        // Create cart item
        $cartItem = new PackagedProductCartItem("virtual-host");

        $cartItem->setPlan("BUDGET");
        $cartItem->addAddOn("BUDGET_5GB");
        $cartItem->addAddOn("ACCOUNT_MANAGER");

        // Grab the unit price
        $unitPrice = $cartItem->getUnitPrice("GBP", 1);

        // Now grab the prices for each package
        $packagePrice = $this->service->getPackage("virtual-host", "BUDGET")->getTierPrice(1, Recurrence::MONTHLY, "GBP") +
            $this->service->getPackage("virtual-host", "BUDGET_5GB")->getTierPrice(1, Recurrence::MONTHLY, "GBP") +
            $this->service->getPackage("virtual-host", "ACCOUNT_MANAGER")->getTierPrice(1, Recurrence::MONTHLY, "GBP");

        $this->assertEquals($packagePrice, $unitPrice);

    }


}

