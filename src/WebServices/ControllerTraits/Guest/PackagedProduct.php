<?php

namespace Kinicart\Webservices\ControllerTraits\Guest;

use Kinicart\Objects\Product\PackagedProduct\Package;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductCartItem;
use Kinicart\Services\Cart\SessionCart;
use Kinicart\Services\Product\PackagedProduct\PackagedProductService;
use Kinicart\WebServices\ValueObjects\Product\PackagedProduct\PackagedProductCartItemDescriptor;

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
     * Constructor for auto-wiring
     *
     * PackagedProduct constructor.
     * @param PackagedProductService $packagedProductService
     * @param SessionCart $sessionCart
     */
    public function __construct($packagedProductService, $sessionCart) {
        $this->packagedProductService = $packagedProductService;
        $this->sessionCart = $sessionCart;
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
        return $this->packagedProductService->getAllPlans($productIdentifier);
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
        return $this->packagedProductService->getAllGlobalAddOns($productIdentifier);
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
        $cartItem = new PackagedProductCartItem($productIdentifier, $packagedProductCartItemDescriptor->getPlanIdentifier(), $packagedProductCartItemDescriptor->getAddOnIdentifiers());
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
