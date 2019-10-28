<?php


namespace Kinicart\Services\Product;

use Kinicart\Objects\Cart\ProductCartItem;
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


    /**
     * Process a product cart item - delegate to the product implementation
     *
     * @param $accountId
     * @param ProductCartItem $cartItem
     */
    public function processProductCartItem($accountId, $cartItem) {
        $product = $this->getProduct($cartItem->getProductIdentifier());
        $product->processCartItem($accountId, $cartItem);
    }


    // Load product classes if required
    private function loadProductClasses() {
        if (!$this->productClasses) {
            $config = new ConfigFile("Config/products.txt");
            $this->productClasses = $config->getAllParameters();
        }
    }


}
