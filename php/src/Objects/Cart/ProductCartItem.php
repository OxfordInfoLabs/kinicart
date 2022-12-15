<?php


namespace Kinicart\Objects\Cart;

use Kinicart\Objects\Account\Account;
use Kinicart\Services\Product\ProductService;
use Kinikit\Core\DependencyInjection\Container;

/**
 * Product Cart item
 *
 * Class ProductCartItem
 *
 * @package Kinicart\Objects\Cart
 */
abstract class ProductCartItem extends CartItem {

    /**
     * String product identifier.
     *
     * @var string
     */
    protected $productIdentifier;


    /**
     * ProductCartItem constructor.
     *
     * @param string $productIdentifier
     */
    public function __construct($productIdentifier) {
        $this->productIdentifier = $productIdentifier;
    }


    /**
     * @return string
     */
    public function getProductIdentifier() {
        return $this->productIdentifier;
    }


    /**
     * @param Account $account
     */
    public function onComplete($account) {
        // Process a product cart item
        $productService = Container::instance()->get(ProductService::class);
        $productService->processProductCartItem($account, $$this);
    }

}
