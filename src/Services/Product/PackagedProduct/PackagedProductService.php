<?php


namespace Kinicart\Services\Product\PackagedProduct;

use Kinicart\Objects\Product\PackagedProduct\Package;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductCartItem;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductFeature;
use Kinicart\Objects\Product\PackagedProduct\PackagedProductSubscriptionPackage;
use Kinicart\Services\Product\ProductService;
use Kinikit\Core\Util\ObjectArrayUtils;
use Kinikit\Persistence\ORM\ORM;

/**
 * Service for managing packaged products and their lifecycle.
 *
 * Class PackagedProductService
 */
class PackagedProductService {

    /**
     * @var ProductService
     */
    private $productService;


    /**
     * @var ORM
     */
    private $orm;

    /**
     * PackagedProductService constructor.
     *
     * @param ProductService $productService
     * @param ORM $orm
     */
    public function __construct($productService, $orm) {
        $this->productService = $productService;
        $this->orm = $orm;
    }


    /**
     * Get a packaged product by identifier
     *
     * @param $identifier
     * @return PackagedProduct
     */
    public function getPackagedProduct($identifier) {
        $product = $this->productService->getProduct($identifier);
        if (!$product || !$product instanceof PackagedProduct) {
            throw new \Exception("The supplied identifier does not match a packaged product");
        }
        return $product;
    }

    /**
     * Get all product features for the supplied product identifier.
     *
     * @param $productIdentifier
     * @return PackagedProductFeature[]
     */
    public function getAllProductFeatures($productIdentifier) {

        // Now get the persisted ones
        $productFeatures = ObjectArrayUtils::indexArrayOfObjectsByMember("featureIdentifier", PackagedProductFeature::filter("WHERE productIdentifier = ?", $productIdentifier));

        // Get the list of code defined features
        $features = $this->getPackagedProduct($productIdentifier)->getFeatures();

        $returnedProductFeatures = [];
        foreach ($features as $feature) {
            if (isset($productFeatures[$feature->getIdentifier()])) {
                $returnedProductFeatures[] = $productFeatures[$feature->getIdentifier()];
            } else {
                $returnedProductFeatures[] = new PackagedProductFeature($productIdentifier, $feature);
            }
        }

        return $returnedProductFeatures;

    }

    /**
     * Save product featurs
     *
     * @param PackagedProductFeature[] $productFeatures
     */
    public function saveProductFeatures($productFeatures) {
        $this->orm->save($productFeatures);
    }


    /**
     * Get a package by product identifier and package identifier (either numerical id or string identifier).
     *
     * @param string $productIdentifier
     * @param string $packageIdentifier
     *
     * @return Package
     */
    public function getPackage($productIdentifier, $packageIdentifier) {
        return Package::fetch([$productIdentifier, $packageIdentifier]);
    }


    /**
     * Get all top level plans
     *
     * @param $productIdentifier
     * @return Package[]
     */
    public function getAllPlans($productIdentifier) {
        return Package::filter("WHERE productIdentifier = ? AND type = 'PLAN' AND parent_identifier IS NULL ORDER BY upgradeOrder,features.id", $productIdentifier);
    }


    /**
     * Get all global add ons (i.e. not ones associated with a plan).
     *
     * @param $productIdentifier
     * @return Package[]
     */
    public function getAllGlobalAddOns($productIdentifier) {
        return Package::filter("WHERE productIdentifier = ? AND type = 'ADD_ON' AND parent_identifier IS NULL ORDER BY upgradeOrder,features.id", $productIdentifier);

    }


    /**
     * Save an array of package objects
     *
     * @param Package|Package[] $packages
     */
    public function savePackages($packages) {
        $this->orm->save($packages);
    }


    /**
     * Delete a package by primary key
     *
     * @param string $productIdentifier
     * @param string $packageIdentifier
     */
    public function deletePackage($productIdentifier, $packageIdentifier) {
        $package = $this->getPackage($productIdentifier, $packageIdentifier);
        $package->remove();
    }


    /**
     * Save subscription packages for a subscription id and a packaged product cart item.
     *
     * @param integer $subscriptionId
     * @param PackagedProductCartItem $packagedProductCartItem
     */
    public function saveSubscriptionPackages($subscriptionId, $packagedProductCartItem) {


        // Remove any previous packages
        $previousSubPackages = PackagedProductSubscriptionPackage::filter("WHERE subscriptionId = ?", $subscriptionId);
        $this->orm->delete($previousSubPackages);
        
        $subscriptionPackages = [];

        // Add the plan
        $plan = $packagedProductCartItem->getPlan();
        $subscriptionPackages[] = new PackagedProductSubscriptionPackage($subscriptionId, $plan->getProductIdentifier(), $plan->getIdentifier());


        $addOnQuantities = $packagedProductCartItem->getAddOnQuantities();
        foreach ($packagedProductCartItem->getAddOns() as $index => $addOn) {
            $subscriptionPackages[] = new PackagedProductSubscriptionPackage($subscriptionId, $addOn->getProductIdentifier(), $addOn->getIdentifier(), $addOnQuantities[$index]);
        }

        $this->orm->save($subscriptionPackages);
    }


}
