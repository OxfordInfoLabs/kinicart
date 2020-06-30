<?php

namespace Kinicart\Objects\Account;

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
        $authenticationService = Container::instance()->get(AuthenticationService::class);
        $authenticationService->login("sam@samdavisdesign.co.uk", AuthenticationHelper::encryptPasswordForLogin("password"));

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

}
