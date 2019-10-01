<?php


namespace Kinicart\Services\Product\PackagedProduct;

use Kinicart\Objects\Product\PackagedProduct\PackagedProduct;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductFeature;
use Kinicart\Services\Product\ProductService;
use Kinikit\Core\Util\ObjectArrayUtils;

/**
 * Service for managing packaged products and their lifecycle.
 *
 * Class PackagedProductService
 */
class PackagedProductService {

    /**
     * @var ProductService
     */
    private $productService;


    /**
     * PackagedProductService constructor.
     *
     * @param ProductService $productService
     */
    public function __construct($productService) {
        $this->productService = $productService;
    }


    /**
     * Get a packaged product by identifier
     *
     * @param $identifier
     * @return PackagedProduct
     */
    public function getPackagedProduct($identifier) {
        $product = $this->productService->getProduct($identifier);
        if (!$product || !$product instanceof PackagedProduct) {
            throw new \Exception("The supplied identifier does not match a packaged product");
        }
        return $product;
    }

    /**
     * Get all product features for the supplied product identifier.
     *
     * @param $productIdentifier
     * @return PackagedProductFeature[]
     */
    public function getAllProductFeatures($productIdentifier) {
        
        // Now get the persisted ones
        $productFeatures = ObjectArrayUtils::indexArrayOfObjectsByMember("featureIdentifier", PackagedProductFeature::filter("WHERE productIdentifier = ?", $productIdentifier));

        // Get the list of code defined features
        $features = $this->getPackagedProduct($productIdentifier)->getFeatures();

        $returnedProductFeatures = [];
        foreach ($features as $feature) {
            if (isset($productFeatures[$feature->getIdentifier()])) {
                $productFeature = $productFeatures[$feature->getIdentifier()];
                $productFeature->setFeature($feature);
                $returnedProductFeatures[] = $productFeature;
            } else {
                $returnedProductFeatures[] = new PackagedProductFeature($productIdentifier, $feature);
            }
        }

        return $returnedProductFeatures;

    }


}
