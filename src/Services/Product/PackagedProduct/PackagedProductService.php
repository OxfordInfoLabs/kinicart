<?php


namespace Kinicart\Services\Product\PackagedProduct;

use Kinicart\Objects\Product\PackagedProduct\PackagedProduct;
use Kinicart\Services\Product\ProductService;

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


}
