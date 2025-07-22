<?php

namespace Kinicart\Traits\Controller\Admin;

use Kinicart\Services\Pricing\TierService;

trait Tier {

    /**
     * @var TierService
     */
    private TierService $tierService;

    public function __construct($tierService) {
        $this->tierService = $tierService;
    }

    /**
     * Get the available tiers
     * Option to not show private ones
     *
     * @http GET /
     *
     * @param bool $publicOnly
     * @return \Kinicart\Objects\Pricing\Tier[]
     */
    public function getTiers(bool $publicOnly = true): array {
        return $this->tierService->getTiers($publicOnly);
    }

}