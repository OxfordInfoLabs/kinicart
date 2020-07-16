<?php


namespace Kinicart\Services\Product\PackagedProduct;


use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductCartItem;
use Kinicart\Services\Product\ProductService;
use Kinicart\Services\Subscription\SubscriptionService;
use Kinicart\TestBase;
use Kinicart\ValueObjects\Product\PackagedProduct\PackagedProductCartItemDescriptor;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Testing\MockObject;
use Kinikit\Core\Testing\MockObjectProvider;

class PackagedProductTest extends TestBase {


    /**
     * @var MockObject
     */
    private $productService;

    /**
     * @var MockObject
     */
    private $subscriptionService;

    /**
     * @var MockObject
     */
    private $packagedProductService;

    /**
     * @var PackagedProduct
     */
    private $packagedProduct;


    public function setUp(): void {

        /**
         * @var MockObjectProvider $mockObjectProvider
         */
        $mockObjectProvider = Container::instance()->get(MockObjectProvider::class);

        $this->subscriptionService = $mockObjectProvider->getMockInstance(SubscriptionService::class);
        $this->packagedProductService = $mockObjectProvider->getMockInstance(PackagedProductService::class);
        $this->productService = $mockObjectProvider->getMockInstance(ProductService::class);

        $this->packagedProduct = new VirtualHost($this->productService, $this->subscriptionService, $this->packagedProductService);
    }


    public function testSubscriptionPackageEntriesAreSavedOnProcessCartItem() {

        $cartItem = new PackagedProductCartItem("virtual-host", new PackagedProductCartItemDescriptor("ENTERPRISE"));
        $account = new Account();

        $this->subscriptionService->returnValue("createNewSubscription", 25);


        $this->packagedProduct->processCartItem($account, $cartItem);

        $this->assertTrue($this->packagedProductService->methodWasCalled("saveSubscriptionPackages", [25, $cartItem]));

    }

}
