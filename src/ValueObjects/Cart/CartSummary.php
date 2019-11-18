<?php
namespace Kinicart\ValueObjects\Cart;

use Kinicart\Objects\Cart\Cart;

class CartSummary {

    /**
     * @var CartItemSummary[]
     */
    private $items = [];

    private $cartSummary;

    private $total;

    private $subtotal;

    private $taxes;

    private $currencyString;

    /**
     * CartSummary constructor.
     * @param Cart $cart
     */
    public function __construct($cart) {
        $accountProvider = $cart->getAccountProvider();

        $itemSummary = [];

        foreach ($cart->getItems() as $cartItem) {
            $this->items[] = new CartItemSummary($cartItem, $accountProvider);
            $itemSummary[$cartItem->getTitle()][] = $cartItem;
        }

        $cartSummary = [];
        foreach ($itemSummary as $title => $cartItems) {
            $cartSummary[] = sizeof($cartItems) . " " . $title;
        }
        $this->cartSummary = join("<br>", $cartSummary);

        $account = $accountProvider->provideAccount();
        $this->currencyString = "";
        switch($account->getAccountData()->getCurrencyCode()) {
            case "USD":
                $this->currencyString = "$";
                break;
            case "GBP":
                $this->currencyString = "£";
                break;
            case "EUR":
                $this->currencyString = "€";
                break;
        }

        $this->subtotal = number_format($cart->getTotal(), 2, ".", "");
        $this->taxes = number_format(round($this->subtotal * 0.20, 2), 2, ".", "");
        $this->total = number_format($this->subtotal + $this->taxes, 2, ".", "");
    }

    /**
     * @return mixed
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * @return string
     */
    public function getCartSummary() {
        return $this->cartSummary;
    }

    /**
     * @return string
     */
    public function getTotal() {
        return $this->currencyString . $this->total;
    }

    /**
     * @return float
     */
    public function getSubtotal() {
        return $this->currencyString . $this->subtotal;
    }

    /**
     * @return string
     */
    public function getTaxes() {
        return $this->currencyString . $this->taxes;
    }


}
