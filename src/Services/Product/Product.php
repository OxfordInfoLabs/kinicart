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


}
