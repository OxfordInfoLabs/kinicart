<?php


namespace Kinicart\ValueObjects\Product\PackagedProduct;


use Kinicart\Objects\Product\PackagedProduct\Feature;
use Kinicart\Objects\Product\PackagedProduct\Package;
use Kinicart\Services\Account\AccountProvider;

class PackageSummary {

    /**
     * @var string
     */
    private $title;


    /**
     * @var string
     */
    private $description;


    /**
     * @var PackageFeatureSummary[]
     */
    private $packageFeatures;

    /**
     * @var PackageFeatureSummary[]
     */
    private $excessFeatures;

    /**
     * @var integer
     */
    private $upgradeOrder;


    /**
     * Array of related add ons if any exist
     *
     * @var PackageSummary[]
     */
    private $relatedAddOns;


    /**
     * The active price for this package in active currency
     * indexed by recurrence type.
     *
     * @var float[string]
     */
    private $activePrices;


    /**
     * All tier prices in active currency - indexed by recurrence type and then tier id.
     *
     * @var float[string][string]
     */
    private $allTierPrices;

    /**
     * The working currency for convenience.
     *
     * @var string
     */
    private $workingCurrency;

    /**
     * Construct with a package object
     *
     * @param Package $package
     * @param AccountProvider $accountProvider
     */
    public function __construct($package, $accountProvider) {
        $this->title = $package->getTitle();
        $this->description = $package->getDescription();

        $this->packageFeatures = [];
        $this->excessFeatures = [];
        foreach ($package->getFeatures() as $feature) {
            if ($productFeature = $feature->getProductFeature()) {
                if ($productFeature->getFeature()->getType() === Feature::TYPE_PACKAGE) {
                    $this->packageFeatures[] = new PackageFeatureSummary($feature);
                } else if ($productFeature->getFeature()->getType() === Feature::TYPE_EXCESS) {
                    $this->excessFeatures[] = new PackageFeatureSummary($feature);
                }
            } else {
                $this->packageFeatures[] = new PackageFeatureSummary($feature);
            }
        }

        $this->upgradeOrder = $package->getUpgradeOrder();

        $this->relatedAddOns = [];
        foreach ($package->getChildPackages() ?? [] as $childPackage) {
            $this->relatedAddOns[] = new PackageSummary($childPackage, $accountProvider);
        }

        // Get the account object to use for pricing this package
        $account = $accountProvider->provideAccount();

        $this->workingCurrency = $account->getAccountData()->getCurrencyCode();

        // Grab all prices for the passed currency
        $this->allTierPrices = $package->getAllTierPrices($this->workingCurrency);

        $this->activePrices = [];
        foreach ($this->allTierPrices as $recurrenceType => $tiers) {
            $this->activePrices[$recurrenceType] = $tiers[$account->getAccountData()->getTierId()];
        }


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
     * @return PackageFeatureSummary[]
     */
    public function getPackageFeatures() {
        return $this->packageFeatures;
    }

    /**
     * @return PackageFeatureSummary[]
     */
    public function getExcessFeatures() {
        return $this->excessFeatures;
    }


    /**
     * @return int
     */
    public function getUpgradeOrder() {
        return $this->upgradeOrder;
    }

    /**
     * @return PackageSummary[]
     */
    public function getRelatedAddOns() {
        return $this->relatedAddOns;
    }

    /**
     * @return float
     */
    public function getActivePrices() {
        return $this->activePrices;
    }

    /**
     * @return float
     */
    public function getAllTierPrices() {
        return $this->allTierPrices;
    }

    /**
     * @return string
     */
    public function getWorkingCurrency() {
        return $this->workingCurrency;
    }


}
