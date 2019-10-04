<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinicart\Exception\Product\PackagedProduct\InvalidCartAddOnException;
use Kinicart\Exception\Product\PackagedProduct\NoSuchProductPlanException;
use Kinicart\Objects\Cart\CartItem;
use Kinicart\Services\Product\PackagedProduct\PackagedProductService;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;

/**
 * Class PackagedProductCartItem
 * @package Kinicart\Objects\Product\PackagedProduct
 *
 * @noGenerate
 */
abstract class PackagedProductCartItem extends CartItem {

    /**
     * String product identifier.
     *
     * @var string
     */
    private $productIdentifier;

    /**
     * The main plan forming this product instance.
     *
     * @var Package
     */
    private $plan;

    /**
     * Addons to the plan augmenting this product instance.
     *
     * @var Package[]
     */
    private $addOns;

    /**
     * PackagedProductCartItem constructor.
     *
     * @param Package $plan
     * @param Package[] $addOns
     */
    public function __construct($productIdentifier, $plan = null, $addOns = []) {
        $this->productIdentifier = $productIdentifier;

        if ($plan)
            $this->setPlan($plan);

        if ($addOns) {
            foreach ($addOns as $addOn) {
                $this->addAddOn($addOn);
            }
        }
    }


    /**
     * Set the plan for this cart item.
     *
     * @param string $planIdentifier
     */
    public function setPlan($planIdentifier) {

        /**
         * @var $service PackagedProductService
         */
        $service = Container::instance()->get(PackagedProductService::class);

        try {
            $plan = $service->getPackage($this->productIdentifier, $planIdentifier);
            if ($plan->getType() != Package::TYPE_PLAN)
                throw new NoSuchProductPlanException($planIdentifier, $this->productIdentifier);

            $this->plan = $plan;
            return $plan;
        } catch (ObjectNotFoundException $e) {
            throw new NoSuchProductPlanException($planIdentifier, $this->productIdentifier);
        }


    }


    /**
     * Add an add on to this cart item.
     *
     * @param Package $addOnIdentifier
     */
    public function addAddOn($addOnIdentifier) {
        /**
         * @var $service PackagedProductService
         */
        $service = Container::instance()->get(PackagedProductService::class);

        if (!$this->plan) {
            throw new InvalidCartAddOnException($addOnIdentifier, $this->productIdentifier, null);
        }
        try {
            $addOn = $service->getPackage($this->productIdentifier, $addOnIdentifier);
            if ($addOn->getType() != Package::TYPE_ADD_ON)
                throw new InvalidCartAddOnException($addOnIdentifier, $this->productIdentifier, $this->plan->getIdentifier());


            if ($addOn->getParentIdentifier() && $addOn->getParentIdentifier() != $this->plan->getIdentifier()) {
                throw new InvalidCartAddOnException($addOnIdentifier, $this->productIdentifier, $this->plan->getIdentifier());
            }

            $this->addOns[] = $addOn;
            return $addOn;
        } catch (ObjectNotFoundException $e) {
            throw new InvalidCartAddOnException($addOnIdentifier, $this->productIdentifier, $this->plan->getIdentifier());
        }
    }

    /**
     * Get the unit price for this cart item.
     *
     * @param $currency
     * @return string|void
     */
    public function getUnitPrice($currency) {

    }


    /**
     * Get sub items for this packaged product.
     *
     * @return CartItem[]|void
     */
    public function getSubItems() {

        $subItems = [];

        if ($this->plan) {
            $subItems[] = new PackageCartItem($this->plan);
        }

        if ($this->addOns) {
            foreach ($this->addOns as $addOn) {
                $subItems[] = new PackageCartItem($addOn);
            }
        }

        return $subItems;
    }


}
