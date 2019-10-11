<?php


namespace Kinicart\WebServices\ValueObjects\Product\PackagedProduct;


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
     * @var Feature[]
     */
    private $features;


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

        $this->features = [];
        foreach ($package->getFeatures() as $feature) {
            $feature = $feature->getProductFeature() ? $feature->getProductFeature()->getFeature() : new Feature(null, $feature->getTitle(), $feature->getDescription());
            $this->features[] = $feature;
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
     * @return Feature[]
     */
    public function getFeatures() {
        return $this->features;
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
