<?php

namespace Kinicart\Services\Pricing;

use Kinicart\Objects\Pricing\Tier;

class TierService {

    /**
     * Get the tiers
     * Option to show private ones
     *
     * @param bool $publicOnly
     * @return Tier[]
     */
    public function getTiers(bool $publicOnly = true): array {

        if ($publicOnly) {
            return Tier::filter("WHERE private != 1");
        } else {
            return Tier::filter();
        }

    }

}