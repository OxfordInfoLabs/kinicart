<?php

namespace Kinicart\Services\Product;

use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Cart\ProductCartItem;

/**
 * Class Product
 *
 */
interface Product {

    /**
     * Get the title for this product
     *
     * @return string
     */
    public function getTitle();

    /**
     * Get the description for this product
     *
     * @return string
     */
    public function getDescription();


    /**
     * Process a cart item - called during checkout.
     *
     * @param Account $account
     * @param ProductCartItem $cartItem
     * @return mixed
     */
    public function processCartItem($account, $cartItem);


}
