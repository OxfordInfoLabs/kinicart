<?php

namespace Kinicart\Objects\Cart;

use Kiniauth\Controllers\Guest\Auth;
use Kiniauth\Services\Security\AuthenticationService;
use Kiniauth\Test\Services\Security\AuthenticationHelper;
use Kinicart\Objects\Product\PackagedProduct\Package;
use Kinicart\Objects\Product\PackagedProduct\PackageCartItem;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductCartItem;
use Kinicart\Services\Account\SessionAccountProvider;
use Kinicart\TestBase;
use Kinicart\ValueObjects\Product\PackagedProduct\PackagedProductCartItemAddOnDescriptor;
use Kinicart\ValueObjects\Product\PackagedProduct\PackagedProductCartItemDescriptor;
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
     * Set up the tests.
     */
    public function setUp(): void {
        $this->sessionAccountProvider = Container::instance()->get(SessionAccountProvider::class);

    }

    public function testSubTotalTaxesAndTotalForCartIsCalculatedUsingPassedAccountProviderForCurrencyTierAndBillingContactInfo() {

        AuthenticationHelper::login("sam@samdavisdesign.co.uk", "password");

        $cart = new Cart($this->sessionAccountProvider);


        $item1 = new PackagedProductCartItem("virtual-host", new PackagedProductCartItemDescriptor("BUDGET", [new PackagedProductCartItemAddOnDescriptor("BUDGET_5GB")]));
        $item2 = new PackagedProductCartItem("virtual-host", new PackagedProductCartItemDescriptor("SMALL_BUSINESS", [new PackagedProductCartItemAddOnDescriptor("SMALL_BUSINESS_10GB")]));

        $cart->addItem($item1);
        $cart->addItem($item2);

        $cartSubTotal = $cart->getSubTotal();
        $this->assertEquals($item1->getUnitPrice("USD", 3) + $item2->getUnitPrice("USD", 3), $cartSubTotal);

        $this->assertEquals(round($cartSubTotal * 0.2, 2), $cart->getTaxes());
        $this->assertEquals(round($cartSubTotal * 1.2, 2), $cart->getTotal());

        $nonTaxedItem = new SimpleCartItem("Untaxed", null, null, ["USD" => 1], [], false);
        $cart->addItem($nonTaxedItem);

        $this->assertEquals($cartSubTotal + 1, $cart->getSubTotal());
        $this->assertEquals(round($cartSubTotal * 0.2, 2), $cart->getTaxes());
        $this->assertEquals(round(($cartSubTotal * 1.2) + 1, 2), $cart->getTotal());


        // Now login as someone else with zero VAT rate
        AuthenticationHelper::logout();

        AuthenticationHelper::login("simon@peterjonescarwash.com", "password");

        $cartSubTotal = $cart->getSubTotal();
        $this->assertEquals(round($item1->getUnitPrice("GBP", 2) + $item2->getUnitPrice("GBP", 2), 2), $cartSubTotal);

        $this->assertEquals(0, $cart->getTaxes());
        $this->assertEquals(round($cartSubTotal, 2), $cart->getTotal());


    }

}
