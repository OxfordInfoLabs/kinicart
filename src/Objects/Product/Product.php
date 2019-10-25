<?php

namespace Kinicart\Objects\Product;

/**
 * Class Product
 *
 */
abstract class Product {

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

}
