<?php

namespace Kinicart\Webservices\ControllerTraits\Guest;

use Kinicart\Objects\Product\PackagedProduct\Package;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductFeature;
use Kinicart\Services\Product\PackagedProduct\PackagedProductService;

trait PackagedProduct {

    /**
     * @var PackagedProductService
     */
    private $packagedProductService;


    /**
     * Constructor for auto-wiring
     *
     * PackagedProduct constructor.
     * @param PackagedProductService $packagedProductService
     */
    public function __construct($packagedProductService) {
        $this->packagedProductService = $packagedProductService;
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


}
