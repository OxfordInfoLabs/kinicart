<?php


namespace Kinicart\Objects\Cart;


use Kinicart\Objects\Account\Account;
use Kinicart\Services\Account\AccountBalanceService;
use Kinikit\Core\DependencyInjection\Container;

class AccountTopUpCartItem extends CartItem {

    /**
     * @var float
     */
    private $topUpAmount;

    /**
     * AccountTopUpCartItem constructor.
     *
     * @param float $topUpAmount
     */
    public function __construct($topUpAmount) {
        $this->topUpAmount = $topUpAmount;
    }


    /**
     * Return account top up
     *
     * @return string
     */
    public function getType() {
        return "accounttopup";
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle() {
        return "Account Top Up";
    }

    /**
     * Get sub title
     *
     * @return string
     */
    public function getSubtitle() {
        return "Amount: " . $this->topUpAmount;
    }

    /**
     * No description
     *
     * @return string|void
     */
    public function getDescription() {
    }

    /**
     * Return the unit price
     *
     * @param string $currency
     * @param integer $tierId
     *
     * @return float
     */
    public function getUnitPrice($currency, $tierId = null) {
        return $this->topUpAmount;
    }

    /**
     * On complete method - call pricing service to perform top up
     *
     * @param Account $account
     */
    public function onComplete($account) {
        /**
         * @var AccountBalanceService $accountBalanceService
         */
        $accountBalanceService = Container::instance()->get(AccountBalanceService::class);
        $accountBalanceService->topUpBalance($this->topUpAmount, null, $account->getAccountId());
    }
}
