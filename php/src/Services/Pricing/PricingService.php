<?php

namespace Kinicart\Services\Pricing;

use Kiniauth\Services\Application\Session;
use Kiniauth\Services\Security\SecurityService;
use Kinicart\Exception\Pricing\InvalidCurrencyException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Pricing\Currency;
use Kinicart\Objects\Pricing\Tier;
use Kinikit\Core\Configuration\Configuration;
use Kinikit\Core\Exception\WrongParameterTypeException;
use Kinikit\Core\Exception\WrongPropertyTypeException;
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
     * @var Session
     */
    private $session;


    /**
     * @var SecurityService
     */
    private $securityService;

    const ACTIVE_CURRENCY_SESSION_NAME = "activeCurrency";


    /**
     * PricingService constructor.
     *
     * @param Session $session
     * @param SecurityService $securityService
     */
    public function __construct($session, $securityService) {
        $this->session = $session;
        $this->securityService = $securityService;
    }


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

    /**
     * @return Currency
     */
    public function getDefaultCurrency() {
        $currencies = $this->getCurrencies();
        foreach ($currencies as $currency) {
            if ($currency->isDefaultCurrency())
                return $currency;
        }

        return null;
    }

    /**
     * @return Tier
     */
    public function getDefaultTier() {

        $tiers = $this->getTiers();
        foreach ($tiers as $tier) {
            if ($tier->isDefaultTier())
                return $tier;
        }

        return null;

    }


    /**
     * Get the active currency - either pulled from the logged in account
     * or from the session if no account logged in.
     */
    public function getActiveCurrency() {

        $loggedInAccount = $this->securityService->getLoggedInSecurableAndAccount()[1];

        if ($loggedInAccount) {
            $currencyCode = $loggedInAccount->getAccountData()->getCurrencyCode();
        } else if ($this->session->getValue(self::ACTIVE_CURRENCY_SESSION_NAME)) {
            $currencyCode = $this->session->getValue(self::ACTIVE_CURRENCY_SESSION_NAME);
        } else {
            $currencyCode = $this->getDefaultCurrency()->getCode();
        }

        return $this->getCurrencies()[$currencyCode] ?? $this->getActiveCurrency();

    }


    /**
     * Set the active currency - sets session value and also synchronises
     * the user record for next time if logged in.
     *
     * @param $activeCurrency
     */
    public function setActiveCurrencyCode($activeCurrencyCode) {

        $loggedInAccount = $this->securityService->getLoggedInSecurableAndAccount()[1];


        if ($loggedInAccount) {

            // Get the active currency and set it.
            $activeCurrency = $this->getCurrencies()[$activeCurrencyCode] ?? $this->getDefaultCurrency()->getCode();

            // Update the account data
            $account = Account::fetch($loggedInAccount->getAccountId());
            $account->getAccountData()->setCurrency($activeCurrency);
            $account->save();

            // Reload the session objects
            $this->securityService->reloadLoggedInObjects();


        } else {
            $this->session->setValue(self::ACTIVE_CURRENCY_SESSION_NAME, $activeCurrencyCode);
        }
    }


    /**
     * Get the active tier - either from logged in user or return default.
     */
    public function getActiveTierId() {

        $loggedInAccount = $this->securityService->getLoggedInSecurableAndAccount()[1];

        if ($loggedInAccount) {
            return $loggedInAccount->getAccountData()->getTierId();
        } else {
            return $this->getDefaultTier()->getId();
        }
    }

    /**
     * Convert an amount supplied as a decimal in source currency to target currency
     *
     * @param float $amount
     * @param string $sourceCurrencyCode
     * @param string $targetCurrencyCode
     *
     * @throws InvalidCurrencyException
     * @throws WrongPropertyTypeException
     */
    public function convertAmountToCurrency($amount, $sourceCurrencyCode, $targetCurrencyCode, $precision = 2) {

        // Check amount is numeric
        if (!is_numeric($amount)) {
            throw new WrongParameterTypeException("Cannot convert a non-numeric amount from one currency to another");
        }

        $allCurrencies = $this->getCurrencies();
        if (!isset($allCurrencies[$sourceCurrencyCode])) throw new InvalidCurrencyException($sourceCurrencyCode);
        if (!isset($allCurrencies[$targetCurrencyCode])) throw new InvalidCurrencyException($targetCurrencyCode);

        // Do conversion only if required
        if ($sourceCurrencyCode != $targetCurrencyCode)
            $amount = $amount / $allCurrencies[$sourceCurrencyCode]->getExchangeRateFromBase() * $allCurrencies[$targetCurrencyCode]->getExchangeRateFromBase();


        // Return formatted number
        return number_format(round($amount, $precision), $precision, '.', '');
    }


}
