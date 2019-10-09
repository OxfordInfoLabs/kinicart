<?php

namespace Kinicart\Services\Pricing;

use Kinicart\Objects\Pricing\Currency;
use Kinicart\Objects\Pricing\Tier;
use Kinicart\TestBase;

include_once __DIR__ . "/../../autoloader.php";

class PricingServiceTest extends TestBase {

    /**
     * @var PricingService
     */
    private $service;

    public function setUp(): void {
        $this->service = new PricingService();
    }

    public function testCanGetAllCurrenciesAndTheyAreCachedOnSubsequentRequests() {

        $currencies = $this->service->getCurrencies();
        $this->assertEquals(["GBP" => Currency::fetch("GBP"), "USD" => Currency::fetch("USD"), "EUR" => Currency::fetch("EUR")], $currencies);

        $reCurrencies = $this->service->getCurrencies();
        $this->assertTrue($currencies === $reCurrencies);

    }


    public function testCanGetAllTiersAndTheyAreCachedOnSubsequentRequests() {

        $tiers = $this->service->getTiers();
        $this->assertEquals([1 => Tier::fetch(1), 2 => Tier::fetch(2),3 => Tier::fetch(3)], $tiers);

        $reTiers = $this->service->getTiers();
        $this->assertTrue($tiers === $reTiers);

    }

}
