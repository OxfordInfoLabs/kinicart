<?php


namespace Kinicart\Objects\Pricing;

use Kinicart\Exception\Pricing\InvalidCurrencyException;
use Kinicart\Exception\Pricing\InvalidTierException;
use Kinicart\Services\Pricing\PricingService;
use Kinikit\Core\DependencyInjection\Container;

/**
 * Pricing for a product with a specified recurrence.
 *
 * Class ProductRecurrencePrice
 * @package Kinicart\Objects\Pricing
 *
 * @table kc_product_base_price
 */
class ProductBasePrice {


    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $productIdentifier;


    /**
     * @var string
     */
    private $itemIdentifier;


    /**
     * Recurrence Type - one of the recurrence constants
     *
     * @var string
     * @required
     */
    private $recurrenceType = self::RECURRENCE_ONE_OFF;


    /**
     * Quantity of the recurrence (defaults to 1)
     *
     * @var integer
     * @required
     */
    private $recurrence = 1;


    /**
     * Base price for this product in this recurrence
     *
     * @var float
     * @required
     */
    private $basePrice;


    /**
     * Currency code for which the price is being defined.
     *
     * @var string
     * @required
     */
    private $currencyCode;


    /**
     * Explicit tier prices for this base price if any have been set.
     *
     * @oneToMany
     * @childJoinColumns base_price_id
     *
     * @var ProductTierPrice[]
     */
    private $tierPrices;


    // Recurrence types for this class.
    const RECURRENCE_ONE_OFF = "ONE_OFF";
    const RECURRENCE_MONTHLY = "MONTHLY";
    const RECURRENCE_ANNUAL = "ANNUAL";

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getProductIdentifier() {
        return $this->productIdentifier;
    }

    /**
     * @param string $productIdentifier
     */
    public function setProductIdentifier($productIdentifier) {
        $this->productIdentifier = $productIdentifier;
    }

    /**
     * @return string
     */
    public function getItemIdentifier() {
        return $this->itemIdentifier;
    }

    /**
     * @param string $itemIdentifier
     */
    public function setItemIdentifier($itemIdentifier) {
        $this->itemIdentifier = $itemIdentifier;
    }

    /**
     * @return string
     */
    public function getRecurrenceType() {
        return $this->recurrenceType;
    }

    /**
     * @param string $recurrenceType
     */
    public function setRecurrenceType($recurrenceType) {
        $this->recurrenceType = $recurrenceType;
    }

    /**
     * @return int
     */
    public function getRecurrence() {
        return $this->recurrence;
    }

    /**
     * @param int $recurrence
     */
    public function setRecurrence($recurrence) {
        $this->recurrence = $recurrence;
    }

    /**
     * @return float
     */
    public function getBasePrice() {
        return $this->basePrice;
    }

    /**
     * @param float $basePrice
     */
    public function setBasePrice($basePrice) {
        $this->basePrice = $basePrice;
    }

    /**
     * @return string
     */
    public function getCurrencyCode() {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode($currencyCode) {
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return ProductTierPrice[]
     */
    public function getTierPrices() {
        return $this->tierPrices;
    }

    /**
     * @param ProductTierPrice[] $tierPrices
     */
    public function setTierPrices($tierPrices) {
        $this->tierPrices = $tierPrices;
    }

    /**
     * Get a tier price in the supplied currency.  If one has been supplied, use it - otherwise
     * derive one from our base price.
     *
     * @param $currency
     * @param $tierId
     */
    public function getTierPrice($currencyCode, $tierId) {

        $price = null;

        // Check for explicit prices first.
        if ($this->tierPrices) {

            $priceCurrency = null;
            foreach ($this->tierPrices as $tierPrice) {
                if ($tierId == $tierPrice->getTierId()) {
                    $price = $tierPrice->getTierPrice();
                    $priceCurrency = $tierPrice->getCurrencyCode();
                    if ($tierPrice->getCurrencyCode() == $currencyCode)
                        break;
                }
            }

            // If we didn't manage to get an explicit currency code, do the conversion now.
            if (is_numeric($price) && $tierPrice->getCurrencyCode() != $currencyCode) {
                $currencies = Container::instance()->get(PricingService::class)->getCurrencies();

                // Check currencies exist.
                if (!isset($currencies[$priceCurrency])) throw new InvalidCurrencyException($priceCurrency);
                if (!isset($currencies[$currencyCode])) throw new InvalidCurrencyException($currencyCode);

                $price = $price / $currencies[$priceCurrency]->getExchangeRateFromBase() * $currencies[$currencyCode]->getExchangeRateFromBase();
            }

        }


        // If still no price, use base price to derive tier.
        if ($price == null) {

            $pricingService = Container::instance()->get(PricingService::class);

            // Do the tier conversion.
            $tiers = $pricingService->getTiers();
            if (!isset($tiers[$tierId])) throw new InvalidTierException($tierId);
            $tier = $tiers[$tierId];
            $price = $this->basePrice * $tier->getDefaultPriceMultiplier();

            // If we need to do a currency conversion, do this too.
            if ($currencyCode != $this->currencyCode) {
                $currencies = $pricingService->getCurrencies();

                if (!isset($currencies[$this->currencyCode])) throw new InvalidCurrencyException($this->currencyCode);
                if (!isset($currencies[$currencyCode])) throw new InvalidCurrencyException($currencyCode);

                $price = $price / $currencies[$this->currencyCode]->getExchangeRateFromBase() * $currencies[$currencyCode]->getExchangeRateFromBase();
            }


        }


        return is_numeric($price) ? number_format(round($price, 2), 2, '.', '') : null;
    }


}
