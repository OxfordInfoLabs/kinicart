<?php

namespace Kinicart\Objects\Product;

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


}
