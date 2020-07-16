<?php


namespace Kinicart\ValueObjects\Product\PackagedProduct;


use Kinicart\Objects\Product\PackagedProduct\Package;
use Kinicart\Services\Account\AccountProvider;

class PackageAddOnSummary extends PackageSummary {

    /**
     * @var PackageFeatureSummary[]
     */
    private $parentFeatures;


    /**
     * PackageAddOnSummary constructor.
     *
     * @param Package $addOn
     * @param PackageSummary $parentPackage
     * @param AccountProvider $accountProvider
     */
    public function __construct($addOn, $parentPackage, $accountProvider) {

        parent::__construct($addOn, $accountProvider);

        $this->parentFeatures = $parentPackage->getPackageFeatures();

    }


    /**
     * Return boolean if this is an add on which can be added with multiple quantity.
     */
    public function isQuantityAddOn() {
        return $this->getMaxQuantity() > 1;
    }


    /**
     * Return boolean if this is a single feature upgrade add on.
     *
     * @return bool
     */
    public function isFeatureUpgradeAddOn() {

        $featureKey = array_keys($this->getPackageFeatures())[0];

        return sizeof($this->getPackageFeatures()) == 1 && $featureKey &&
            in_array($featureKey, array_keys($this->parentFeatures));

    }


    /**
     * Only returned if this is a feature upgrade
     *
     * @return PackageFeatureSummary
     */
    public function getParentIncludedFeature() {

        if ($this->isFeatureUpgradeAddOn()) {
            $upgradeFeature = array_keys($this->getPackageFeatures())[0];
            return $this->parentFeatures[$upgradeFeature];
        } else {
            return null;
        }

    }

    /**
     * Only returned if this is a feature upgrade
     *
     * @return PackageFeatureSummary
     */
    public function getUpgradeFeature() {
        if ($this->isFeatureUpgradeAddOn()) {
            $upgradeFeature = array_keys($this->getPackageFeatures())[0];
            return $this->getPackageFeatures()[$upgradeFeature];
        } else {
            return null;
        }
    }


}
