<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinicart\Exception\Product\PackagedProduct\CartItemAddOnDoesNotExistException;
use Kinicart\Exception\Product\PackagedProduct\InvalidCartAddOnException;
use Kinicart\Exception\Product\PackagedProduct\MaximumAddOnQuantityExceededException;
use Kinicart\Exception\Product\PackagedProduct\NoSuchProductPlanException;
use Kinicart\Objects\Cart\CartItem;
use Kinicart\Objects\Cart\SubscriptionCartItem;
use Kinicart\Objects\Pricing\ProductBasePrice;
use Kinicart\Services\Product\PackagedProduct\PackagedProductService;
use Kinicart\Services\Product\ProductService;
use Kinicart\Types\Recurrence;
use Kinicart\ValueObjects\Product\PackagedProduct\PackagedProductCartItemDescriptor;
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
    private $productTitle;


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
     * @var integer[]
     */
    private $addOnQuantities = [];


    /**
     * PackagedProductCartItem constructor.
     *
     * @param string $productIdentifier
     * @param PackagedProductCartItemDescriptor $cartItemDescriptor
     *
     * @throws InvalidCartAddOnException
     * @throws NoSuchProductPlanException
     */
    public function __construct($productIdentifier, $cartItemDescriptor = null) {

        // Construct the parent subscription cart item with required logic.
        parent::__construct($productIdentifier, $cartItemDescriptor ? $cartItemDescriptor->getRecurrenceType() : Recurrence::MONTHLY);

        /**
         * Lookup product and store title and description for later use.
         *
         * @var ProductService $productService
         */
        $productService = Container::instance()->get(ProductService::class);
        $product = $productService->getProduct($productIdentifier);
        $this->productTitle = $product->getTitle();

        if ($cartItemDescriptor) {

            if ($cartItemDescriptor->getPlanIdentifier())
                $this->setPlan($cartItemDescriptor->getPlanIdentifier());

            if ($cartItemDescriptor->getAddOnDescriptors()) {
                foreach ($cartItemDescriptor->getAddOnDescriptors() as $addOn) {
                    $this->addAddOn($addOn->getAddOnIdentifier(), $addOn->getQuantity());
                }
            }
        }
    }

    public function getType() {
        return $this->getProductIdentifier();
    }


    /**
     * Implement sub type to supply the plan identifier.
     *
     * @return mixed|string
     */
    public function getSubType() {
        return $this->plan ? $this->plan->getIdentifier() : "";
    }

    /**
     * Get title for this product cart item
     *
     * @return string|void
     */
    public function getTitle() {
        return $this->productTitle;
    }


    /**
     * Get subtitle
     *
     * @return mixed|string
     */
    public function getSubtitle() {
        return $this->plan ? $this->plan->getTitle() : "";
    }

    /**
     * Get description for this product cart item.
     *
     * @return string|void
     */
    public function getDescription() {
        return $this->plan ? $this->plan->getDescription() : "";
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


    public function getAddOnQuantities() {
        return $this->addOnQuantities;
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
                    array_splice($this->addOnQuantities, $i, 1);
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
    public function addAddOn($addOnIdentifier, $quantity = 1) {
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


            // Check for existing add on of same type.
            $existingAddOnFound = false;
            foreach ($this->addOns as $index => $compareAddOn) {

                if ($compareAddOn->getIdentifier() == $addOnIdentifier) {
                    $newQuantity = $this->addOnQuantities[$index] + $quantity;

                    if ($newQuantity > $addOn->getMaxQuantity())
                        throw new MaximumAddOnQuantityExceededException($addOnIdentifier, $this->productIdentifier, $this->plan->getIdentifier());

                    $this->addOnQuantities[$index] = $newQuantity;
                    $existingAddOnFound = true;
                    break;
                }
            }

            if (!$existingAddOnFound) {

                if ($quantity > $addOn->getMaxQuantity())
                    throw new MaximumAddOnQuantityExceededException($addOnIdentifier, $this->productIdentifier, $this->plan->getIdentifier());

                $this->addOns[] = $addOn;
                $this->addOnQuantities[] = $quantity;
            }


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
            array_splice($this->addOnQuantities, $addOnIndex, 1);
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
            foreach ($this->addOns as $index => $addOn) {
                $quantity = $this->addOnQuantities[$index];
                $price += $quantity * ($addOn->getTierPrice($tierId, $this->recurrenceType, $currency));
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

        if ($this->addOns) {
            foreach ($this->addOns as $index => $addOn) {
                $subItems[] = new PackageCartItem($addOn, Recurrence::MONTHLY, $this->addOnQuantities[$index]);
            }
        }

        return $subItems;
    }


}
