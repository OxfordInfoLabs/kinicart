<?php


namespace Kinicart\Services\Product;

use Kiniauth\Services\Workflow\PendingActionService;
use Kinicart\Objects\Account\Account;
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
            $instance = Container::instance()->get($productClass);
            $instance->identifier = $identifier;
            return $instance;
        }
    }


    /**
     * Add a product dynamically to the list of products
     *
     * @param $identifier
     * @param $className
     */
    public function addProduct($identifier, $className) {
        $this->loadProductClasses();
        $this->productClasses[$identifier] = $className;
    }


    /**
     * Process a product cart item - delegate to the product implementation
     *
     * @param Account $accountId
     * @param ProductCartItem $cartItem
     */
    public function processProductCartItem($account, $cartItem) {
        $product = $this->getProduct($cartItem->getProductIdentifier());
        $product->processCartItem($account, $cartItem);
    }


    // Load product classes if required
    private function loadProductClasses() {
        if (!$this->productClasses) {
            $config = new ConfigFile("Config/products.txt");
            $this->productClasses = $config->getAllParameters();
        }
    }


}
