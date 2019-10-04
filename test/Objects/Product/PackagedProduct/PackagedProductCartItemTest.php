<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinicart\Exception\Product\PackagedProduct\InvalidCartAddOnException;
use Kinicart\Exception\Product\PackagedProduct\NoSuchProductPlanException;
use Kinicart\Services\Product\PackagedProduct\PackagedProductService;
use Kinicart\TestBase;
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


    public function testCanCreateCartItemAndSetPlanProvidedItIsValid() {

        // Create cart item
        $cartItem = new ConcretePackagedProductCartItem("virtual-host");


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
        $cartItem = new ConcretePackagedProductCartItem("virtual-host");


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


}

