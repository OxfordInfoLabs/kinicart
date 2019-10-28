<?php

namespace Kinicart\Services\Product\PackagedProduct;


use Kinicart\Objects\Product\PackagedProduct\Feature;
use Kinicart\Services\Product\SubscriptionProduct;

/**
 * Base class for a product, should be extended to make concrete application products.
 *
 * Class Product
 */
abstract class PackagedProduct extends SubscriptionProduct {

    /**
     * Get a list of features which this product makes available.  Features are combined
     * into packages for sale purposes.
     *
     * @return Feature[]
     */
    public abstract function getFeatures();




}
