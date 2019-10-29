<?php


namespace Kinicart\WebServices\ValueObjects\Product\PackagedProduct;


use Kinicart\Objects\Product\PackagedProduct\PackageFeature;

class PackageFeatureSummary {

    private $identifier;

    private $title;

    private $description;

    private $quantity = null;

    /**
     * PackageFeatureSummary constructor.
     * @param PackageFeature $feature
     */
    public function __construct($feature) {
        $this->quantity = $feature->getQuantity();
        $this->identifier = $feature->getFeatureIdentifier();
        if ($feature->getProductFeature()) {
            $productFeature = $feature->getProductFeature()->getFeature();
            $this->title = $productFeature->getTitle();
            $this->description = $productFeature->getDescription();
        } else {
            $this->title = $feature->getTitle();
            $this->description = $feature->getDescription();
        }

    }

    /**
     * @return string
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @return float|null
     */
    public function getQuantity() {
        return $this->quantity;
    }

}
