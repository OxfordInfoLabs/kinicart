<?php


namespace Kinicart\Test\Services\Order;


use Kiniauth\Services\Security\AuthenticationService;
use Kinicart\Objects\Cart\CartItem;
use Kinicart\Objects\Order\Order;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductCartItem;
use Kinicart\Services\Cart\SessionCart;
use Kinicart\Services\Order\OrderService;
use Kinicart\TestBase;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Testing\MockObject;
use Kinikit\Core\Testing\MockObjectProvider;

include_once __DIR__ . "/../../autoloader.php";

class OrderServiceTest extends TestBase {

    /**
     * @var OrderService
     */
    private $service;
    /**
     * @var SessionCart
     */
    private $sessionCart;


    /**
     * @var MockObjectProvider
     */
    private $mockObjectProvider;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    public function setUp(): void {
        parent::setUp();
        $this->service = Container::instance()->get(OrderService::class);
        $this->sessionCart = Container::instance()->get(SessionCart::class);
        $this->mockObjectProvider = Container::instance()->get(MockObjectProvider::class);
        $this->authenticationService = Container::instance()->get(AuthenticationService::class);

    }

    public function testCanProcessOrder() {

        $this->authenticationService->login("sam@samdavisdesign.co.uk", "password");

        $cart = $this->sessionCart->get();

        /**
         * @var MockObject $cartItem1
         */
        $cartItem1 = $this->mockObjectProvider->getMockInstance(CartItem::class);
        $cartItem1->returnValue("getTitle", "Mr Blobby");
        $cartItem1->returnValue("getUnitPrice", 4.20);
        $cart->addItem($cartItem1);

        /**
         * @var MockObject $cartItem2
         */
        $cartItem2 = $this->mockObjectProvider->getMockInstance(CartItem::class);
        $cartItem2->returnValue("getTitle", "Mr Alan");
        $cartItem2->returnValue("getUnitPrice", 8.50);
        $cart->addItem($cartItem2);

        /** @var Order $order */
        $order = $this->service->processOrder(1, 1);

        $this->assertTrue($cartItem1->methodWasCalled("process",[]));
        $this->assertTrue($cartItem2->methodWasCalled("process",[]));

//        $this->assertTrue(stripos($order->getPaymentData()));


    }

}
