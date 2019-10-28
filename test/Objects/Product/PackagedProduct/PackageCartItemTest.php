<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinicart\Objects\Cart\SimpleCartItem;
use Kinicart\Objects\Pricing\ProductBasePrice;
use Kinicart\Services\Product\PackagedProduct\PackagedProductService;
use Kinicart\TestBase;
use Kinicart\Types\Recurrence;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Logging\Logger;

include_once __DIR__ . "/../../../autoloader.php";

class PackageCartItemTest extends TestBase {


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


    public function testTitleAndDescriptionReadFromPackageForCartItem() {

        $package = $this->service->getPackage("virtual-host", "BUDGET");

        // Package cart item
        $cartItem = new PackageCartItem($package);

        $this->assertEquals($package->getTitle(), $cartItem->getTitle());
        $this->assertEquals($package->getDescription(), $cartItem->getDescription());

    }


    public function testFeaturesAreAddedAsSubCartItems() {

        $package = $this->service->getPackage("virtual-host", "BUDGET");

        // Package cart item
        $cartItem = new PackageCartItem($package);

        $subItems = $cartItem->getSubItems();

        // Check one to one mapping of features to sub cart items.
        foreach ($package->getFeatures() as $index => $feature) {
            $this->assertEquals(new SimpleCartItem($feature->getFeatureIdentifier() ? $feature->getProductFeature()->getFeature()->getTitle() : $feature->getTitle(),
                $feature->getFeatureIdentifier() ? $feature->getProductFeature()->getFeature()->getDescription() : $feature->getDescription()), $subItems[$index]);
        }


    }


    public function testUnitPriceIsCalculatedFromPackage() {

        $package = $this->service->getPackage("virtual-host", "SMALL_BUSINESS");

        $cartItem = new PackageCartItem($package);

        $unitPrice = $cartItem->getUnitPrice("GBP", 1);
        $this->assertEquals($package->getTierPrice(1, Recurrence::MONTHLY, "GBP"), $unitPrice);


        $cartItem = new PackageCartItem($package, Recurrence::ANNUAL);

        $unitPrice = $cartItem->getUnitPrice("USD", 2);
        $this->assertEquals($package->getTierPrice(2, Recurrence::ANNUAL, "USD"), $unitPrice);

    }


}
