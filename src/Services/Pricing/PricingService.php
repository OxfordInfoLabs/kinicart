<?php

namespace Kinicart\Services\Pricing;

use Kinicart\Objects\Pricing\Currency;
use Kinicart\Objects\Pricing\Tier;
use Kinikit\Core\Util\ObjectArrayUtils;

class PricingService {

    /**
     * @var Currency[string]
     */
    private $currencies;


    /**
     * @var Tier[string]
     */
    private $tiers;

    /**
     * Get all currencies indexed by currency code in an efficient manner, caching after first read.
     *
     * @return Currency[string]
     */
    public function getCurrencies() {
        if (!$this->currencies) {
            $this->currencies = ObjectArrayUtils::indexArrayOfObjectsByMember("code", Currency::filter(""));
        }
        return $this->currencies;
    }


    /**
     * Get all tiers indexed by id in an efficient manner, caching after first read.
     */
    public function getTiers() {
        if (!$this->tiers) {
            $this->tiers = ObjectArrayUtils::indexArrayOfObjectsByMember("id", Tier::filter(""));
        }
        return $this->tiers;
    }

}
