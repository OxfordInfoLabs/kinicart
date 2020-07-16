<?php

namespace Kinicart\Traits\Controller\Guest;

use Kinicart\Exception\Cart\CartItemDoesNotExistsException;
use Kinicart\Objects\Product\PackagedProduct\Package;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductCartItem;
use Kinicart\Services\Account\SessionAccountProvider;
use Kinicart\Services\Cart\SessionCart;
use Kinicart\Services\Product\PackagedProduct\PackagedProductService;
use Kinicart\ValueObjects\Product\PackagedProduct\PackagedProductCartItemDescriptor;
use Kinicart\ValueObjects\Product\PackagedProduct\PackageSummary;
use Kinikit\Core\Logging\Logger;


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
     * @return PackageSummary[]
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
     * Get a single product plan
     *
     * @http GET /plans/$productIdentifier/$plan
     *
     * @param $productIdentifier
     * @param $plan
     *
     * @return PackageSummary
     */
    public function getProductPlan($productIdentifier, $plan) {
        $plan = $this->packagedProductService->getPackage($productIdentifier, $plan);
        return new PackageSummary($plan, $this->sessionAccountProvider);
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
    public function updateProductCartItem($productIdentifier, $packagedProductCartItemDescriptor, $cartItemIndex = null) {
        $cartItem = new PackagedProductCartItem($productIdentifier, $packagedProductCartItemDescriptor);
        if (is_numeric($cartItemIndex)) {
            try {
                $this->sessionCart->updateItem($cartItemIndex, $cartItem);
            } catch (CartItemDoesNotExistsException $e){
                $this->sessionCart->addItem($cartItem);
            }
        } else
            $this->sessionCart->addItem($cartItem);

        return 1;
    }


}
