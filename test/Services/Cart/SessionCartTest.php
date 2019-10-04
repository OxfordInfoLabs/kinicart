<?php

namespace Kinicart\Services\Cart;

use Kiniauth\Services\Application\Session;
use Kinicart\Exception\Cart\CartItemDoesNotExistsException;
use Kinicart\Objects\Cart\Cart;
use Kinicart\Objects\Cart\SimpleCartItem;
use Kinicart\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once __DIR__ . "/../../autoloader.php";

class SessionCartTest extends TestBase {

    /**
     * @var Session
     */
    private $session;

    /**
     * @var SessionCart
     */
    private $sessionCart;

    public function setUp(): void {
        $this->session = Container::instance()->get(Session::class);
        $this->sessionCart = Container::instance()->get(SessionCart::class);
        $this->session->setValue(SessionCart::CART_SESSION_NAME, null);
    }

    public function testIfNoSessionCartExistsGetCreatesANewOne() {

        $this->assertNull($this->session->getValue(SessionCart::CART_SESSION_NAME));

        $cart = $this->sessionCart->get();
        $this->assertEquals(new Cart(), $cart);

        $this->assertEquals(new Cart(), $this->session->getValue(SessionCart::CART_SESSION_NAME));


    }


    public function testCanAddItemsToTheSessionCart() {

        $this->sessionCart->addItem(new SimpleCartItem("Mark", "Bingo"));

        $cart = $this->sessionCart->get();
        $this->assertEquals(1, sizeof($cart->getItems()));
        $this->assertEquals(new SimpleCartItem("Mark", "Bingo"), $cart->getItems()[0]);

        $this->sessionCart->addItem(new SimpleCartItem("James", "Bongo"));

        $cart = $this->sessionCart->get();
        $this->assertEquals(2, sizeof($cart->getItems()));
        $this->assertEquals(new SimpleCartItem("Mark", "Bingo"), $cart->getItems()[0]);
        $this->assertEquals(new SimpleCartItem("James", "Bongo"), $cart->getItems()[1]);


        $this->assertEquals($this->session->getValue(SessionCart::CART_SESSION_NAME), $cart);


    }


    public function testCanUpdateItemsInSessionCartProvidedItemExists() {

        $this->sessionCart->addItem(new SimpleCartItem("Mark", "Bingo"));
        $this->sessionCart->addItem(new SimpleCartItem("Dave", "Bongo"));

        try {
            $this->sessionCart->updateItem(2, new SimpleCartItem("Monkey", "Gorilla"));
            $this->fail("Should have thrown here");
        } catch (CartItemDoesNotExistsException $e) {
            // Success
        }

        $this->sessionCart->updateItem(1, new SimpleCartItem("Pig", "Pongo"));


        $cart = $this->sessionCart->get();
        $this->assertEquals(2, sizeof($cart->getItems()));
        $this->assertEquals(new SimpleCartItem("Mark", "Bingo"), $cart->getItems()[0]);
        $this->assertEquals(new SimpleCartItem("Pig", "Pongo"), $cart->getItems()[1]);


    }


    public function testCanRemoveItemsFromSessionCartProvidedTheyExist() {

        $this->sessionCart->addItem(new SimpleCartItem("Mark", "Bingo"));
        $this->sessionCart->addItem(new SimpleCartItem("Dave", "Bongo"));
        $this->sessionCart->addItem(new SimpleCartItem("Chef", "Pickle"));

        try {
            $this->sessionCart->removeItem(3);
            $this->fail("Should have thrown here");
        } catch (CartItemDoesNotExistsException $e) {
            // Success
        }


        $this->sessionCart->removeItem(1);


        $cart = $this->sessionCart->get();
        $this->assertEquals(2, sizeof($cart->getItems()));
        $this->assertEquals(new SimpleCartItem("Mark", "Bingo"), $cart->getItems()[0]);
        $this->assertEquals(new SimpleCartItem("Chef", "Pickle"), $cart->getItems()[1]);


        $this->sessionCart->removeItem(0);

        $cart = $this->sessionCart->get();
        $this->assertEquals(1, sizeof($cart->getItems()));
        $this->assertEquals(new SimpleCartItem("Chef", "Pickle"), $cart->getItems()[0]);

    }


    public function testCanClearCart() {

        $this->sessionCart->addItem(new SimpleCartItem("Mark", "Bingo"));
        $this->sessionCart->addItem(new SimpleCartItem("Dave", "Bongo"));
        $this->sessionCart->addItem(new SimpleCartItem("Chef", "Pickle"));

        $this->sessionCart->clear();

        $cart = $this->sessionCart->get();
        $this->assertEquals(new Cart(), $cart);

    }
}
