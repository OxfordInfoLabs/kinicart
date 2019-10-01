<?php

namespace Kinicart\Webservices\ControllerTraits\Admin;

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


}
