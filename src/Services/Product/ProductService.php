<?php


namespace Kinicart\Services\Product;

use Kinicart\Objects\Product\Product;
use Kinikit\Core\Configuration\ConfigFile;
use Kinikit\Core\DependencyInjection\Container;

/**
 * Generic product service - operates on all products
 *
 * Class ProductService
 */
class ProductService {

    /**
     * @var string[]
     */
    private $productClasses = null;

    /**
     * Get a product by identifier
     *
     * @param $identifier
     * @return Product
     */
    public function getProduct($identifier) {
        $this->loadProductClasses();
        $productClass = $this->productClasses[$identifier] ?? null;
        if ($productClass) {
            return Container::instance()->get($productClass);
        }
    }


    // Load product classes if required
    private function loadProductClasses() {
        if (!$this->productClasses) {
            $config = new ConfigFile("Config/products.txt");
            $this->productClasses = $config->getAllParameters();
        }
    }

}
