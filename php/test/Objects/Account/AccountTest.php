<?php

namespace Kinicart\Objects\Account;

use Kiniauth\Objects\Security\AccountRole;
use Kiniauth\Objects\Security\Role;
use Kiniauth\Services\Application\Session;
use Kiniauth\Services\Security\AuthenticationService;
use Kiniauth\Test\Services\Security\AuthenticationHelper;
use Kinicart\TestBase;
use Kinikit\Core\DependencyInjection\Container;


include_once __DIR__ . "/../../autoloader.php";

class AccountTest extends TestBase {

    /**
     * Account retrieval functions return the cart version
     */
    public function testKiniauthAccountRetrievalFunctionsReturnCartVersion() {

        /**
         * @var $authenticationService AuthenticationService
         */

        AuthenticationHelper::login("sam@samdavisdesign.co.uk", "password");

        $account = \Kiniauth\Objects\Account\Account::fetch(1);

        $this->assertTrue($account instanceof Account);
        $this->assertTrue($account->getAccountData() instanceof AccountData);
        $this->assertEquals(3, $account->getAccountData()->getTierId());
        $this->assertEquals("USD", $account->getAccountData()->getCurrencyCode());
    }


    public function testCurrencyAndTierDefaultsToDefaultVersionsIfNotSet() {


        $session = Container::instance()->get(Session::class);
        $session->setValue("activeCurrency", null);

        /**
         * @var Account $account
         */
        $account = Account::fetch(1);
        $this->assertEquals(3, $account->getAccountData()->getTierId());
        $this->assertEquals("USD", $account->getAccountData()->getCurrencyCode());

        // Now try a default one.
        $account = Account::fetch(5);
        $this->assertEquals(1, $account->getAccountData()->getTierId());
        $this->assertEquals("GBP", $account->getAccountData()->getCurrencyCode());


    }


    public function testVATRateReturnedCorrectlyIfBillingContactConfigured() {

        /**
         * @var Account $account
         */
        $account = Account::fetch(1);
        $accountData = $account->getAccountData();

        $billingContact = $accountData->getBillingContact();
        $this->assertEquals("Joe Bloggs", $billingContact->getName());
        $this->assertEquals(20, $billingContact->getVatRatePercentage());

        $billingContact->setCountryCode("FR");
        $billingContact->save();

        $account = Account::fetch(1);
        $accountData = $account->getAccountData();

        $billingContact = $accountData->getBillingContact();
        $this->assertEquals("Joe Bloggs", $billingContact->getName());
        $this->assertEquals(17, $billingContact->getVatRatePercentage());

        $billingContact->setCountryCode("US");
        $billingContact->save();

        $account = Account::fetch(1);
        $accountData = $account->getAccountData();

        $billingContact = $accountData->getBillingContact();
        $this->assertEquals("Joe Bloggs", $billingContact->getName());
        $this->assertEquals(0, $billingContact->getVatRatePercentage());

        $billingContact->setCountryCode("GB");
        $billingContact->save();

    }

    public function testAccountRolesAreAugmentedWithTierRolesIfSuppliedForUserTier() {

        AuthenticationHelper::login("sam@samdavisdesign.co.uk", "password");

        /**
         * @var Account $account
         */
        $account = Account::fetch(1);
        $this->assertEquals(["ACCOUNT" => ["viewdata"]], $account->returnAccountPrivileges());

    }

}
