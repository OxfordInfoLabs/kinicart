<?php

namespace Kinicart\WebServices\ControllerTraits\Admin;

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
     * Get all product features for a given product (passed by identifier).
     *
     * @http GET /features/$productIdentifier
     *
     * @param $productIdentifier
     */
    public function getProductFeatures($productIdentifier) {
        return $this->packagedProductService->getAllProductFeatures($productIdentifier);
    }


    /**
     * Save all product features passed
     *
     * @http PUT /features
     *
     * @param PackagedProductFeature[] $productFeatures
     */
    public function saveProductFeatures($productFeatures) {
        $this->packagedProductService->saveProductFeatures($productFeatures);
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
     * Save one or more packages (either Plan or Add on).
     *
     * @http PUT /packages
     *
     * @param Package[] $packages
     */
    public function savePackages($packages) {
        $this->packagedProductService->savePackages($packages);
    }


    /**
     * Delete a package using it's primary key (product identifier + package identifier)
     *
     * @http DELETE /package/$productIdentifier/$packageIdentifier
     *
     * @param string $productIdentifier
     * @param string $packageIdentifier
     */
    public function deletePackage($productIdentifier, $packageIdentifier) {
        $this->packagedProductService->deletePackage($productIdentifier, $packageIdentifier);
    }

}
