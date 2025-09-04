<?php

namespace Kinicart\Test\Services\Pricing;

use Kinicart\Services\Pricing\TierService;
use Kinicart\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once "autoloader.php";

class TierServiceTest extends TestBase {

    private $tierService;

    public function setUp(): void {
        $this->tierService = Container::instance()->get(TierService::class);
    }

    public function testCanGetTiers() {

        $tiers = $this->tierService->getTiers();
        $this->assertCount(3, $tiers);

        $allTiers = $this->tierService->getTiers(false);
        $this->assertCount(4, $allTiers);
    }

}