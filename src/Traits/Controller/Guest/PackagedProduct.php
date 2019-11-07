<?php

namespace Kinicart\Traits\Controller\Guest;

use Kinicart\Objects\Product\PackagedProduct\Package;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductCartItem;
use Kinicart\Services\Account\SessionAccountProvider;
use Kinicart\Services\Cart\SessionCart;
use Kinicart\Services\Product\PackagedProduct\PackagedProductService;
use Kinicart\ValueObjects\Product\PackagedProduct\PackagedProductCartItemDescriptor;
use Kinicart\ValueObjects\Product\PackagedProduct\PackageSummary;


trait PackagedProduct {

    /**
     * @var PackagedProductService
     */
    private $packagedProductService;

    /**
     * @var SessionCart
     */
    private $sessionCart;


    /**
     * @var SessionAccountProvider
     */
    private $sessionAccountProvider;


    /**
     * Constructor for auto-wiring
     *
     * PackagedProduct constructor.
     * @param PackagedProductService $packagedProductService
     * @param SessionCart $sessionCart
     * @param SessionAccountProvider $sessionAccountProvider
     */
    public function __construct($packagedProductService, $sessionCart, $sessionAccountProvider) {
        $this->packagedProductService = $packagedProductService;
        $this->sessionCart = $sessionCart;
        $this->sessionAccountProvider = $sessionAccountProvider;
    }


    /**
     * Get all plans for a given product
     *
     * @http GET /plans/$productIdentifier
     *
     * @param $productIdentifier
     * @return Package[]
     */
    public function getProductPlans($productIdentifier) {
        $productPlans = $this->packagedProductService->getAllPlans($productIdentifier);
        $summaries = [];
        foreach ($productPlans as $productPlan) {
            $summaries[] = new PackageSummary($productPlan, $this->sessionAccountProvider);
        }

        return $summaries;
    }


    /**
     * Get all global add ons for a given product
     *
     * @http GET /addons/$productIdentifier
     *
     * @param $productIdentifier
     * @return Package[]
     */
    public function getGlobalAddOns($productIdentifier) {
        $addOns = $this->packagedProductService->getAllGlobalAddOns($productIdentifier);
        $summaries = [];
        foreach ($addOns as $addOn) {
            $summaries[] = new PackageSummary($addOn, $this->sessionAccountProvider);
        }

        return $summaries;
    }


    /**
     * Add a packaged product to the cart.  This identifies the product identifier
     * and the payload contains data about the product to add.
     *
     * @http POST /cartitem/$productIdentifier
     *
     * @param string $productIdentifier
     * @param PackagedProductCartItemDescriptor $packagedProductCartItemDescriptor
     */
    public function addProductToCart($productIdentifier, $packagedProductCartItemDescriptor) {
        $cartItem = new PackagedProductCartItem($productIdentifier, $packagedProductCartItemDescriptor);
        $this->sessionCart->addItem($cartItem);
    }


    /**
     * Update the plan for a cart item identified by index.
     *
     * @http PUT /cartitem/plan/$cartItemIndex/$planIdentifier
     *
     * @param integer $cartItemIndex
     * @param string $planIdentifier
     */
    public function updateCartItemPlan($cartItemIndex, $planIdentifier) {
        $cartItem = $this->sessionCart->getItem($cartItemIndex);
        if ($cartItem instanceof PackagedProductCartItem) {
            $cartItem->setPlan($planIdentifier);
            $this->sessionCart->updateItem($cartItemIndex, $cartItem);
        }
    }


    /**
     * Add the specified add on to the cart item
     *
     * @http POST /cartitem/addon/$cartItemIndex/$addOnIdentifier
     *
     * @param integer $cartItemIndex
     * @param string $addOnIdentifier
     */
    public function addAddOnToCartItem($cartItemIndex, $addOnIdentifier) {
        $cartItem = $this->sessionCart->getItem($cartItemIndex);
        if ($cartItem instanceof PackagedProductCartItem) {
            $cartItem->addAddOn($addOnIdentifier);
            $this->sessionCart->updateItem($cartItemIndex, $cartItem);
        }
    }


    /**
     *
     * @param integer $cartItemIndex
     * @param integer $addOnIndex
     */
    public function removeAddOnFromCartItem($cartItemIndex, $addOnIndex) {
        $cartItem = $this->sessionCart->getItem($cartItemIndex);
        if ($cartItem instanceof PackagedProductCartItem) {
            $cartItem->removeAddOn($addOnIndex);
            $this->sessionCart->updateItem($cartItemIndex, $cartItem);
        }
    }


}
