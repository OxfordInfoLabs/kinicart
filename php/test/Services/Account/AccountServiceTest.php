<?php


namespace Kinicart\Test\Services\Account;


use Kiniauth\Test\Services\Security\AuthenticationHelper;
use Kinicart\Services\Account\AccountService;
use Kinicart\TestBase;
use Kinicart\ValueObjects\Account\BillingContact;
use Kinikit\Core\DependencyInjection\Container;

include_once "autoloader.php";

class AccountServiceTest extends TestBase {

    /**
     * @var AccountService
     */
    private $accountService;


    public function setUp(): void {
        $this->accountService = Container::instance()->get(AccountService::class);
    }


    public function testCanGetBillingContactForAccountIdOrNullIfNoneExists() {

        AuthenticationHelper::login("admin@kinicart.com", "password");

        $billingContact = $this->accountService->getBillingContact(1);
        $this->assertEquals(new BillingContact("Joe Bloggs", "Show caser", "1 New Place", "Sometown", "Somewhere", "Someshire", "SW12 1TT", "GB"), $billingContact);

        $this->assertNull($this->accountService->getBillingContact(2));

    }

    public function testCanUpdateBillingContactForAccountId() {

        AuthenticationHelper::login("admin@kinicart.com", "password");
        $billingContact = new BillingContact("David Jones", "Test org", "My Place", "Some Street", "Oxford", "Oxon", "OX44 1EE", "FR");

        $this->accountService->updateBillingContact($billingContact, 1);
        $this->assertEquals($billingContact, $this->accountService->getBillingContact(1));

        $this->accountService->updateBillingContact($billingContact, 2);
        $this->assertEquals($billingContact, $this->accountService->getBillingContact(2));

        $this->accountService->updateBillingContact(new BillingContact("Joe Bloggs", "Show caser", "1 New Place", "Sometown", "Somewhere", "Someshire", "SW12 1TT", "GB"), 1);

    }




}