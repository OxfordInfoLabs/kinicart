<?php

namespace Kinicart\Services\Pricing;

use Kiniauth\Services\Application\Session;
use Kiniauth\Services\Security\SecurityService;
use Kinicart\Objects\Account\Account;
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

        $loggedInAccount = $this->securityService->getLoggedInUserAndAccount()[1];

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

        $loggedInAccount = $this->securityService->getLoggedInUserAndAccount()[1];


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

        $loggedInAccount = $this->securityService->getLoggedInUserAndAccount()[1];

        if ($loggedInAccount) {
            return $loggedInAccount->getAccountData()->getTierId();
        } else {
            return $this->getDefaultTier()->getId();
        }
    }

}
