<?php


namespace Kinicart\ValueObjects\Product\PackagedProduct;


use Kinicart\Objects\Product\PackagedProduct\PackageFeature;

class PackageFeatureSummary {

    private $identifier;

    private $title;

    private $description;

    private $unit;

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
            $this->unit = $productFeature->getUnit();
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

    /**
     * @return mixed
     */
    public function getUnit() {
        return $this->unit;
    }

}
