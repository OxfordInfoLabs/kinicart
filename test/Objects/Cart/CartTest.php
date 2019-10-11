<?php

namespace Kinicart\Objects\Cart;

use Kiniauth\Services\Security\AuthenticationService;
use Kinicart\Objects\Product\PackagedProduct\Package;
use Kinicart\Objects\Product\PackagedProduct\PackageCartItem;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductCartItem;
use Kinicart\Services\Account\SessionAccountProvider;
use Kinicart\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once __DIR__ . "/../../autoloader.php";

/**
 * Test cases for the cart.
 *
 * Class CartTest
 */
class CartTest extends TestBase {

    /**
     * @var SessionAccountProvider
     */
    private $sessionAccountProvider;


    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * Set up the tests.
     */
    public function setUp(): void {
        $this->sessionAccountProvider = Container::instance()->get(SessionAccountProvider::class);
        $this->authenticationService = Container::instance()->get(AuthenticationService::class);
    }

    public function testTotalForCartIsCalculatedUsingPassedAccountProviderForCurrencyAndTierInfo() {

        $this->authenticationService->login("sam@samdavisdesign.co.uk", "password");

        $cart = new Cart($this->sessionAccountProvider);


        $item1 = new PackagedProductCartItem("virtual-host", "BUDGET", ["BUDGET_5GB"]);
        $item2 = new PackagedProductCartItem("virtual-host", "SMALL_BUSINESS", ["SMALL_BUSINESS_10GB"]);

        $cart->addItem($item1);
        $cart->addItem($item2);

        $cartTotal = $cart->getTotal();
        $this->assertEquals($item1->getUnitPrice("USD", 3) + $item2->getUnitPrice("USD", 3), $cartTotal);


        // Now login as someone else
        $this->authenticationService->logout();

        $this->authenticationService->login("simon@peterjonescarwash.com", "password");

        $cartTotal = $cart->getTotal();
        $this->assertEquals($item1->getUnitPrice("GBP", 1) + $item2->getUnitPrice("GBP", 1), $cartTotal);


    }

}