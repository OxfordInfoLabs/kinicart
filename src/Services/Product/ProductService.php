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
     * @var PendingActionService
     */
    private $pendingActionService;

    /**
     * @var string[]
     */
    private $productClasses = null;


    /**
     * ProductService constructor.
     *
     * @param PendingActionService $pendingActionService
     */
    public function __construct($pendingActionService) {
        $this->pendingActionService = $pendingActionService;
    }

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


    /**
     * Create a deferred product action for the supplied product identifier,
     * product specific object id and optional data.
     *
     * @param $productIdentifier
     * @param $objectId
     * @param array $data
     */
    public function createDeferredProductAction($productIdentifier, $objectId, $data = []) {
        return $this->pendingActionService->createPendingAction("PRODUCT_DEFERRED", $objectId, $data, null, null, $productIdentifier);
    }


    /**
     * Process all deferred product actions - call the process
     *
     * @return array
     */
    public function processAllDeferredProductActions() {

        $pendingActions = $this->pendingActionService->getAllPendingActionsForType("PRODUCT_DEFERRED");

        // Loop through and call the product method.
        $results = ["processed" => sizeof($pendingActions), "succeeded" => [], "retry" => [], "failed" => []];
        foreach ($pendingActions as $action) {

            $product = $this->getProduct($action->getObjectType());
            if ($product) {
                try {
                    $success = $product->processDeferredAction($action->getObjectId(), $action->getData());
                    if ($success) {
                        $this->pendingActionService->removePendingAction("PRODUCT_DEFERRED", $action->getIdentifier());
                        $results["succeeded"][] = $action->getIdentifier();
                    } else {
                        $results["retry"][] = $action->getIdentifier();
                    }
                } catch (\Exception $e) {
                    $results["failed"][$action->getIdentifier()] = $e->getMessage();
                }
            } else {
                $this->pendingActionService->removePendingAction("PRODUCT_DEFERRED", $action->getIdentifier());
            }
        }

        return $results;

    }


    // Load product classes if required
    private function loadProductClasses() {
        if (!$this->productClasses) {
            $config = new ConfigFile("Config/products.txt");
            $this->productClasses = $config->getAllParameters();
        }
    }


}
