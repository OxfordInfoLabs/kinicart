<?php


namespace Kinicart\Test\Services\Order;


use Kiniauth\Objects\Account\Contact;
use Kiniauth\Services\Application\Session;
use Kiniauth\Services\Communication\Email\EmailService;
use Kiniauth\Services\Security\AuthenticationService;
use Kiniauth\Test\Services\Security\AuthenticationHelper;
use Kinicart\Exception\Payment\InvalidBillingContactException;
use Kinicart\Exception\Payment\InvalidPaymentMethodException;
use Kinicart\Exception\Payment\MissingBillingContactException;
use Kinicart\Exception\Payment\MissingPaymentMethodException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Cart\CartItem;
use Kinicart\Objects\Cart\ProductCartItem;
use Kinicart\Objects\Order\Order;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductCartItem;
use Kinicart\Services\Cart\SessionCart;
use Kinicart\Services\Order\OrderService;
use Kinicart\Services\Product\ProductService;
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

    /**
     * @var MockObject
     */
    private $emailService;

    public function setUp(): void {
        parent::setUp();

        $this->sessionCart = Container::instance()->get(SessionCart::class);
        $this->mockObjectProvider = Container::instance()->get(MockObjectProvider::class);
        $this->authenticationService = Container::instance()->get(AuthenticationService::class);
        $this->emailService = $this->mockObjectProvider->getMockInstance(EmailService::class);
        $session = Container::instance()->get(Session::class);

        $this->service = new OrderService($this->sessionCart, $this->emailService, $session);


    }

    public function testExceptionRaisedIfNullOrInvalidPaymentMethodPassedForNonZeroPayment() {


        AuthenticationHelper::login("sam@samdavisdesign.co.uk", "password");

        $cart = $this->sessionCart->get();

        /**
         * @var MockObject $cartItem1
         */
        $cartItem1 = $this->mockObjectProvider->getMockInstance(ProductCartItem::class);
        $cartItem1->returnValue("getTitle", "Mr Blobby");
        $cartItem1->returnValue("getUnitPrice", 4.20);
        $cart->addItem($cartItem1);


        try {
            $this->service->processOrder(1, 150);
            $this->fail("Should have throw here");
        } catch (InvalidPaymentMethodException $e) {
            $this->assertTrue(true);
        }

        try {
            $this->service->processOrder(1, null);
            $this->fail("Should have throw here");
        } catch (MissingPaymentMethodException $e) {
            $this->assertTrue(true);
        }

    }


    public function testExceptionRaisedIfNullOrInvalidContactIdPassedForNonZeroPayment() {


        AuthenticationHelper::login("sam@samdavisdesign.co.uk", "password");

        $cart = $this->sessionCart->get();

        /**
         * @var MockObject $cartItem1
         */
        $cartItem1 = $this->mockObjectProvider->getMockInstance(ProductCartItem::class);
        $cartItem1->returnValue("getTitle", "Mr Blobby");
        $cartItem1->returnValue("getUnitPrice", 4.20);
        $cart->addItem($cartItem1);


        try {
            $this->service->processOrder(150, 1);
            $this->fail("Should have throw here");
        } catch (InvalidBillingContactException $e) {
            $this->assertTrue(true);
        }

        try {
            $this->service->processOrder(null, 1);
            $this->fail("Should have throw here");
        } catch (MissingBillingContactException $e) {
            $this->assertTrue(true);
        }


    }


    public function testCanProcessOrderForValidContactAndPaymentMethod() {

        AuthenticationHelper::login("sam@samdavisdesign.co.uk", "password");


        $this->sessionCart->clear();
        $cart = $this->sessionCart->get();

        /**
         * @var MockObject $cartItem1
         */
        $cartItem1 = $this->mockObjectProvider->getMockInstance(ProductCartItem::class);
        $cartItem1->returnValue("getTitle", "Mr Blobby");
        $cartItem1->returnValue("getUnitPrice", 4.20);
        $cart->addItem($cartItem1);

        /**
         * @var MockObject $cartItem2
         */
        $cartItem2 = $this->mockObjectProvider->getMockInstance(ProductCartItem::class);
        $cartItem2->returnValue("getTitle", "Mr Alan");
        $cartItem2->returnValue("getUnitPrice", 8.50);
        $cart->addItem($cartItem2);


        /** @var Order $order */
        $orderId = $this->service->processOrder(1, 1);

        $account = Account::fetch(1);

        $this->assertTrue($cartItem1->methodWasCalled("onComplete", [$account]));
        $this->assertTrue($cartItem2->methodWasCalled("onComplete", [$account]));


        $order = Order::fetch($orderId);
        $this->assertEquals("USD", $order->getCurrency());
        $this->assertEquals(12.70, $order->getSubtotal());
        $this->assertEquals(12.70 * 0.2, $order->getTaxes());
        $this->assertEquals(12.70 * 1.2, $order->getTotal());

        $this->assertEquals("Sam Davis", $order->getBuyerName());
        $this->assertEquals(Contact::fetch(1)->getAddressString("<br />"), $order->getAddress());
        $this->assertEquals(Order::ORDER_STATUS_COMPLETED, $order->getStatus());
        $this->assertEquals(1270, $order->getPaymentData()["amount"]);
        $this->assertTrue($order->getPaymentData()["captured"]);
        $this->assertNotNull($order->getPaymentData()["paymentIntent"]);
        $this->assertNotNull($order->getPaymentData()["reference"]);


        $this->assertEquals(2, sizeof($order->getOrderLines()));
        $orderline1 = $order->getOrderLines()[0];
        $this->assertEquals([
            "title" => $cartItem1->getTitle(),
            "subtitle" => $cartItem1->getSubtitle(),
            "description" => $cartItem1->getDescription(),
            "quantity" => $cartItem1->getQuantity(),
            "amount" => number_format($cartItem1->getUnitPrice("USD", $account->getAccountData()->getTierId()), 2, ".", ""),
            "currency" => "USD",
            "subItems" => $cartItem1->getSubItems()
        ], $orderline1);

        $orderline2 = $order->getOrderLines()[1];
        $this->assertEquals([
            "title" => $cartItem2->getTitle(),
            "subtitle" => $cartItem2->getSubtitle(),
            "description" => $cartItem2->getDescription(),
            "quantity" => $cartItem2->getQuantity(),
            "amount" => number_format($cartItem2->getUnitPrice("USD", $account->getAccountData()->getTierId()), 2, ".", ""),
            "currency" => "USD",
            "subItems" => $cartItem2->getSubItems()
        ], $orderline2);

    }


    public function testCanProcessValidZeroRateOrderWithoutContactOrBillingInfo() {

        $this->sessionCart->clear();
        $cart = $this->sessionCart->get();

        /**
         * @var MockObject $cartItem1
         */
        $cartItem1 = $this->mockObjectProvider->getMockInstance(ProductCartItem::class);
        $cartItem1->returnValue("getTitle", "Mr Blobby");
        $cartItem1->returnValue("getUnitPrice", 0);
        $cart->addItem($cartItem1);


        /** @var Order $order */
        $orderId = $this->service->processOrder();

        $account = Account::fetch(1);

        $this->assertTrue($cartItem1->methodWasCalled("onComplete", [$account]));


        $order = Order::fetch($orderId);
        $this->assertEquals("USD", $order->getCurrency());
        $this->assertEquals(0, $order->getSubtotal());
        $this->assertEquals(0, $order->getTaxes());
        $this->assertEquals(0, $order->getTotal());

        $this->assertEquals("Sam Davis Design", $order->getBuyerName());
        $this->assertEquals(null, $order->getAddress());
        $this->assertEquals(Order::ORDER_STATUS_COMPLETED, $order->getStatus());
        $this->assertNotNull($order->getPaymentData()["reference"]);

        $this->assertEquals(1, sizeof($order->getOrderLines()));
        $orderline1 = $order->getOrderLines()[0];
        $this->assertEquals([
            "title" => $cartItem1->getTitle(),
            "subtitle" => $cartItem1->getSubtitle(),
            "description" => $cartItem1->getDescription(),
            "quantity" => $cartItem1->getQuantity(),
            "amount" => number_format($cartItem1->getUnitPrice("USD", $account->getAccountData()->getTierId()), 2, ".", ""),
            "currency" => "USD",
            "subItems" => $cartItem1->getSubItems()
        ], $orderline1);


    }


    
}
