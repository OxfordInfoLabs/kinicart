<?php

namespace Kinicart\Services\Product;

use Kiniauth\Objects\Workflow\PendingAction;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Cart\ProductCartItem;

/**
 * Class Product
 *
 */
abstract class Product {

    /**
     * Public - set by the framework
     *
     * @var string
     */
    public $identifier;

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * Product constructor.
     *
     * @param ProductService $productService
     */
    public function __construct($productService) {
        $this->productService = $productService;
    }


    /**
     * Get the title for this product
     *
     * @return string
     */
    public abstract function getTitle();

    /**
     * Get the description for this product
     *
     * @return string
     */
    public abstract function getDescription();


    /**
     * Process a cart item - called during checkout.
     *
     * @param Account $account
     * @param ProductCartItem $cartItem
     * @return mixed
     */
    public abstract function processCartItem($account, $cartItem);


    /**
     * Process a single deferred action passing the objectId and any data.  Return true from
     * this function to record a successful run and remove the action.
     *
     * @param PendingAction $pendingAction
     *
     * @return boolean
     */
    public function processDeferredAction($objectId, $data) {

    }


    /**
     * Create a product deferred action - This is a convenience function for use in e.g. processCartItem
     * functions to create a deferred action for this product type.
     *
     */
    protected function createDeferredAction($objectId, $data = []) {
        return $this->productService->createDeferredProductAction($this->identifier, $objectId, $data);
    }


}
