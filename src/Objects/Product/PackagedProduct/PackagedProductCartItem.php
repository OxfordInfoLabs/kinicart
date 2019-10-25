<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinicart\Exception\Product\PackagedProduct\CartItemAddOnDoesNotExistException;
use Kinicart\Exception\Product\PackagedProduct\InvalidCartAddOnException;
use Kinicart\Exception\Product\PackagedProduct\NoSuchProductPlanException;
use Kinicart\Objects\Cart\CartItem;
use Kinicart\Objects\Pricing\ProductBasePrice;
use Kinicart\Objects\Subscription\SubscriptionCartItem;
use Kinicart\Services\Product\PackagedProduct\PackagedProductService;
use Kinicart\Services\Product\ProductService;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;

/**
 * Class PackagedProductCartItem
 * @package Kinicart\Objects\Product\PackagedProduct
 *
 */
class PackagedProductCartItem extends SubscriptionCartItem {


    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;


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
    private $addOns = [];


    /**
     * PackagedProductCartItem constructor.
     *
     * @param Package $planIdentifier
     * @param Package[] $addOnIdentifiers
     */
    public function __construct($productIdentifier, $planIdentifier = null, $addOnIdentifiers = [],
                                $recurrenceType = ProductBasePrice::RECURRENCE_MONTHLY) {

        // Construct the parent subscription cart item with required logic.
        parent::__construct($productIdentifier, $recurrenceType);

        /**
         * Lookup product and store title and description for later use.
         *
         * @var ProductService $productService
         */
        $productService = Container::instance()->get(ProductService::class);
        $product = $productService->getProduct($productIdentifier);
        $this->title = $product->getTitle();
        $this->description = $product->getDescription();

        if ($planIdentifier)
            $this->setPlan($planIdentifier);

        if ($addOnIdentifiers) {
            foreach ($addOnIdentifiers as $addOn) {
                $this->addAddOn($addOn);
            }
        }
    }

    /**
     * Get title for this product cart item
     *
     * @return string|void
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Get description for this product cart item.
     *
     * @return string|void
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @return Package
     */
    public function getPlan() {
        return $this->plan;
    }

    /**
     * @return Package[]
     */
    public function getAddOns() {
        return $this->addOns;
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

            for ($i = sizeof($this->addOns) - 1; $i >= 0; $i--) {
                $addOn = $this->addOns[$i];
                if ($addOn->getParentIdentifier() && $addOn->getParentIdentifier() != $plan->getIdentifier()) {
                    array_splice($this->addOns, $i, 1);
                }
            }

            return $plan;
        } catch (ObjectNotFoundException $e) {
            throw new NoSuchProductPlanException($planIdentifier, $this->productIdentifier);
        }


    }


    /**
     * Add an add on to this cart item.
     *
     * @param string $addOnIdentifier
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
     * Remove the specified add on at the supplied index.
     *
     * @param $addOnIndex
     */
    public function removeAddOn($addOnIndex) {
        if (isset($this->addOns[$addOnIndex])) {
            array_splice($this->addOns, $addOnIndex, 1);
        } else {
            throw new CartItemAddOnDoesNotExistException($addOnIndex);
        }
    }


    /**
     * Get the unit price for this cart item.
     *
     * @param $currency
     * @param null $tierId
     * @return string|void
     */
    public function getUnitPrice($currency, $tierId = null) {
        $price = 0;

        if ($this->plan) {
            $price += $this->plan->getTierPrice($tierId, $this->recurrenceType, $currency);
        }

        if ($this->addOns) {
            foreach ($this->addOns as $addOn) {
                $price += $addOn->getTierPrice($tierId, $this->recurrenceType, $currency);
            }
        }

        return $price;

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


    public function process() {
        // TODO: Implement process() method.
    }
}
