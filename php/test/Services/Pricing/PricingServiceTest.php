<?php

namespace Kinicart\Services\Pricing;

use Kiniauth\Services\Security\AuthenticationService;
use Kiniauth\Test\Services\Security\AuthenticationHelper;
use Kinicart\Exception\Pricing\InvalidCurrencyException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Pricing\Currency;
use Kinicart\Objects\Pricing\Tier;
use Kinicart\TestBase;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Exception\WrongParameterTypeException;

include_once __DIR__ . "/../../autoloader.php";

class PricingServiceTest extends TestBase {

    /**
     * @var PricingService
     */
    private $service;


    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    public function setUp(): void {
        $this->service = Container::instance()->get(PricingService::class);
        $this->authenticationService = Container::instance()->get(AuthenticationService::class);
    }

    public function testCanGetAllCurrenciesAndTheyAreCachedOnSubsequentRequests() {

        $currencies = $this->service->getCurrencies();
        $this->assertEquals(["GBP" => Currency::fetch("GBP"), "USD" => Currency::fetch("USD"), "EUR" => Currency::fetch("EUR")], $currencies);

        $reCurrencies = $this->service->getCurrencies();
        $this->assertTrue($currencies === $reCurrencies);

    }


    public function testCanGetAllTiersAndTheyAreCachedOnSubsequentRequests() {

        $tiers = $this->service->getTiers();
        $this->assertEquals([1 => Tier::fetch(1), 2 => Tier::fetch(2), 3 => Tier::fetch(3)], $tiers);

        $reTiers = $this->service->getTiers();
        $this->assertTrue($tiers === $reTiers);

    }


    public function testCanGetDefaultCurrencyAndTier() {

        $defaultCurrency = $this->service->getDefaultCurrency();
        $this->assertEquals("GBP", $defaultCurrency->getCode());

        $defaultTier = $this->service->getDefaultTier();
        $this->assertEquals(1, $defaultTier->getId());


    }


    public function testIfNotLoggedInActiveTierAndCurrencyAreReturnedUsingDefaults() {

        $this->authenticationService->logout();

        $this->assertEquals("GBP", $this->service->getActiveCurrency()->getCode());
        $this->assertEquals(1, $this->service->getActiveTierId());

    }


    public function testIfLoggedInActiveTierAndCurrencyAreReturnedFromUser() {

        AuthenticationHelper::login("sam@samdavisdesign.co.uk", "password");

        $this->assertEquals(3, $this->service->getActiveTierId());
        $this->assertEquals("USD", $this->service->getActiveCurrency()->getCode());

    }


    public function testIfActiveCurrencyUpdatedThisUpdatesUserOrSessionAccordingToLoggedIn() {
        $this->authenticationService->logout();

        $this->service->setActiveCurrencyCode("EUR");
        $this->assertEquals("EUR", $this->service->getActiveCurrency()->getCode());
        $this->assertEquals("EUR", $_SESSION[PricingService::ACTIVE_CURRENCY_SESSION_NAME]);

        AuthenticationHelper::login("sam@samdavisdesign.co.uk", "password");
        $this->assertEquals("USD", $this->service->getActiveCurrency()->getCode());
        $this->service->setActiveCurrencyCode("EUR");
        $this->assertEquals("EUR", $this->service->getActiveCurrency()->getCode());

        $account = Account::fetch(1);
        $this->assertEquals("EUR", $account->getAccountData()->getCurrencyCode());

    }


    public function testIfInvalidValueCurrencyCodesPassedToConvertAmountForSourceOrTargetCurrenciesExceptionRaised() {

        try {
            $this->service->convertAmountToCurrency("BOB", "EUR", "GBP");
            $this->fail("Should have thrown here");
        } catch (WrongParameterTypeException $e) {
            $this->assertTrue(true);
        }

        try {
            $this->service->convertAmountToCurrency(12.00, "KRR", "GBP");
            $this->fail("Should have thrown here");
        } catch (InvalidCurrencyException $e) {
            $this->assertTrue(true);
        }

        try {
            $this->service->convertAmountToCurrency(12.00, "USD", "KRR");
            $this->fail("Should have thrown here");
        } catch (InvalidCurrencyException $e) {
            $this->assertTrue(true);
        }

    }

    public function testIfValidAmountAndCurrencyCodesSuppliedConversionsHappenAsExpected() {

        // No conversion where currency codes match
        $this->assertEquals(20.34, $this->service->convertAmountToCurrency(20.34, "GBP", "GBP"));
        $this->assertEquals(20.34, $this->service->convertAmountToCurrency(20.34, "USD", "USD"));
        $this->assertEquals(20.34, $this->service->convertAmountToCurrency(20.34, "EUR", "EUR"));

        $this->assertEquals(12.00, $this->service->convertAmountToCurrency(10, "GBP", "USD"));
        $this->assertEquals(11.00, $this->service->convertAmountToCurrency(10, "GBP", "EUR"));

        $this->assertEquals(8.33, $this->service->convertAmountToCurrency(10, "USD", "GBP"));
        $this->assertEquals(10.91, $this->service->convertAmountToCurrency(10, "EUR", "USD"));


    }

}
