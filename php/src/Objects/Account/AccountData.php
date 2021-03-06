<?php

namespace Kinicart\Objects\Account;

use Kinicart\Objects\Pricing\Currency;
use Kinicart\Objects\Pricing\Tier;
use Kinicart\Services\Pricing\PricingService;
use Kinikit\Core\DependencyInjection\Container;

/**
 * Account specific properties for kinicart.
 *
 * Class AccountData
 *
 * @table kc_account_data
 * @generate
 */
class AccountData {

    /**
     * Link back to the Kiniauth account.
     *
     * @var integer
     * @primaryKey
     */
    private $accountId;


    /**
     * The tier which this account is currently on.
     *
     * @manyToOne
     * @parentJoinColumns tier_id
     *
     * @var Tier
     */
    private $tier;


    /**
     * The currency which this account is currently using.
     *
     * @manyToOne
     * @parentJoinColumns currency_code
     *
     * @var Currency
     */
    private $currency;

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     */
    public function setAccountId($accountId) {
        $this->accountId = $accountId;
    }


    /**
     * Get currency code
     *
     * @return string
     */
    public function getCurrencyCode() {
        return $this->getCurrency()->getCode();
    }

    /**
     * Get tier id
     *
     * @return integer
     */
    public function getTierId() {
        return $this->getTier()->getId();
    }


    /**
     * Get tier name
     *
     * @return string
     */
    public function getTierName() {
        return $this->getTier()->getName();
    }


    /**
     * @return Currency
     */
    private function getCurrency() {
        if (!$this->currency) {
            $this->currency = Container::instance()->get(PricingService::class)->getDefaultCurrency();
        }
        return $this->currency;
    }

    /**
     * @return Tier
     */
    private function getTier() {
        if (!$this->tier) {
            $this->tier = Container::instance()->get(PricingService::class)->getDefaultTier();
        }
        return $this->tier;
    }

    /**
     * @param Tier $tier
     */
    public function setTier($tier) {
        $this->tier = $tier;
    }

    /**
     * @param Currency $currency
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
    }


}
