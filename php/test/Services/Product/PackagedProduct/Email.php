<?php


namespace Kinicart\Services\Product\PackagedProduct;

use Kinicart\Objects\Product\PackagedProduct\Feature;

/**
 * Class Email
 * @package Kinicart\Objects\Product\PackagedProduct
 *
 * @noGenerate
 */
class Email extends PackagedProduct {


    /**
     * Get a list of features which this product makes available.  Features are combined
     * into packages for sale purposes.
     *
     * @return Feature[]
     */
    public function getFeatures() {
        return [
            new Feature("storage", "Storage (GB)", "The amount of storage space included per user"),
            new Feature("users", "Users", "The number of users currently enabled for email"),
            new Feature("bandwidth", "Bandwidth (GB/month)", "The amount of bandwidth in GB/Month")
        ];
    }

    /**
     * Get the title for this product
     *
     * @return string
     */
    public function getTitle() {
        return "Email";
    }

    /**
     * Get the description for this product
     *
     * @return string
     */
    public function getDescription() {
        return "Email Service";
    }
}
