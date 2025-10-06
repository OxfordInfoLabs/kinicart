<?php

namespace Kinicart\Objects\Account;

use Kiniauth\Attributes\Security\AccessNonActiveScopes;
use Kiniauth\Objects\Account\Contact;
use Kinicart\Objects\Pricing\Currency;
use Kinicart\Objects\Pricing\Tier;
use Kinicart\Objects\Security\TierRole;
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
#[AccessNonActiveScopes]
class AccountData {

    /**
     * Link back to the Kiniauth account.
     *
     * @var integer
     * @primaryKey
     */
    protected $accountId;


    /**
     * The tier which this account is currently on.
     *
     * @manyToOne
     * @parentJoinColumns tier_id
     *
     * @var Tier
     */
    protected $tier;


    /**
     * The currency which this account is currently using.
     *
     * @manyToOne
     * @parentJoinColumns currency_code
     *
     * @var Currency
     */
    protected $currency;


    /**
     * @var BillingContact
     * @oneToOne
     * @childJoinColumns account_id,type=BILLING
     * @readOnly
     *
     */
    protected $billingContact;


    /**
     * AccountData constructor.
     * @param Tier $tier
     * @param Currency $currency
     */
    public function __construct($tier = null, $currency = null) {
        $this->tier = $tier;
        $this->currency = $currency;
    }


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
        return $this->getTier() ? $this->getTier()->getId() : null;
    }


    /**
     * Get tier name
     *
     * @return string
     */
    public function getTierName() {
        return $this->getTier() ? $this->getTier()->getName() : null;
    }


    /**
     * Return tier roles
     *
     * @return string[]
     */
    public function getTierPrivileges() {
        return $this->getTier() ? $this->getTier()->getPrivileges() : [];
    }

    /**
     * @return Currency
     */
    protected function getCurrency() {
        if (!$this->currency) {
            $this->currency = Container::instance()->get(PricingService::class)->getDefaultCurrency();
        }
        return $this->currency;
    }

    /**
     * @return Tier
     */
    protected function getTier() {
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

    /**
     * @return BillingContact
     */
    public function getBillingContact() {
        return $this->billingContact;
    }

    /**
     * @param BillingContact $billingContact
     */
    public function setBillingContact($billingContact) {
        $this->billingContact = $billingContact;
    }


}
