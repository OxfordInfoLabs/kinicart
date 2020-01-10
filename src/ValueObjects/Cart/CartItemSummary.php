<?php

namespace Kinicart\ValueObjects\Cart;

use Kinicart\Controllers\Admin\PackagedProduct;
use Kinicart\Objects\Cart\CartItem;
use Kinicart\Objects\Product\PackagedProduct\Package;
use Kinicart\Services\Account\AccountProvider;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Reflection\ClassInspectorProvider;

class CartItemSummary {

    private $type;

    private $subType;

    private $title;

    private $subtitle;

    private $description;

    private $quantity;

    private $total;


    /**
     * Other bespoke properties for subclassed cart items.
     * These are simply the values of any get accessible properties which
     * are not core.
     *
     * @var string[string]
     */
    private $otherProperties = [];

    /**
     * @var CartItemSummary[]
     */
    private $subItems = [];


    /**
     * CartItemSummary constructor.
     * @param CartItem $cartItem
     * @param AccountProvider $accountProvider
     */
    public function __construct($cartItem, $accountProvider) {
        $this->type = $cartItem->getType();
        $this->subType = $cartItem->getSubType();
        $this->title = $cartItem->getTitle();
        $this->subtitle = $cartItem->getSubtitle();
        $this->description = $cartItem->getDescription();
        $this->quantity = $cartItem->getQuantity();


        $account = $accountProvider->provideAccount();
        $total = $cartItem->getUnitPrice($account->getAccountData()->getCurrencyCode(), $account->getAccountData()->getTierId());

        $account = $accountProvider->provideAccount();
        $currencyString = "";
        switch ($account->getAccountData()->getCurrencyCode()) {
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

        foreach ($cartItem->getSubItems() as $subItem) {
            $this->subItems[] = new CartItemSummary($subItem, $accountProvider);
        }


        /**
         * @var ClassInspectorProvider $classInspectorProvider
         */
        $classInspectorProvider = Container::instance()->get(ClassInspectorProvider::class);
        $classInspector = $classInspectorProvider->getClassInspector(get_class($cartItem));

        foreach ($classInspector->getProperties() as $property) {
            if ($property->getDeclaringClassInspector() == $classInspector) {
                $this->otherProperties[$property->getPropertyName()] = $property->get($cartItem);
            }
        }


    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getSubType() {
        return $this->subType;
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
    public function getSubtitle() {
        return $this->subtitle;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getQuantity() {
        return $this->quantity;
    }


    /**
     * @return float
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * @return string
     */
    public function getOtherProperties() {
        return $this->otherProperties;
    }


    /**
     * @return CartItemSummary[]
     */
    public function getSubItems() {
        return $this->subItems;
    }


}
