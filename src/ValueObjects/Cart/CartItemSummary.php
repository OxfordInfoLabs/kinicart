<?php

namespace Kinicart\ValueObjects\Cart;

use Kinicart\Objects\Cart\CartItem;
use Kinicart\Services\Account\AccountProvider;

class CartItemSummary {

    private $type;

    private $title;

    private $description;

    private $subDescription;

    private $total;

    /**
     * CartItemSummary constructor.
     * @param CartItem $cartItem
     * @param AccountProvider $accountProvider
     */
    public function __construct($cartItem, $accountProvider) {
        $this->type = $cartItem->getType();
        $this->title = $cartItem->getTitle();
        $this->description = $cartItem->getDescription();

        if (sizeof($cartItem->getSubItems()) > 0) {
            $this->subDescription = $cartItem->getSubItems()[0]->getDescription();
        }

        $account = $accountProvider->provideAccount();
        $total = $cartItem->getUnitPrice($account->getAccountData()->getCurrencyCode(), $account->getAccountData()->getTierId());

        $account = $accountProvider->provideAccount();
        $currencyString = "";
        switch($account->getAccountData()->getCurrencyCode()) {
            case "USD":
                $currencyString = "$";
                break;
            case "GBP":
                $currencyString = "£";
                break;
            case "EUR":
                $currencyString = "€";
                break;
        }

        $this->total = $currencyString . number_format($total, 2);

    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getSubDescription() {
        return $this->subDescription;
    }

    /**
     * @return float
     */
    public function getTotal() {
        return $this->total;
    }


}
