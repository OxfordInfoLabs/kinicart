<?php


namespace Kinicart\Test\Services\Account;


use Cassandra\Exception\ConfigurationException;
use Kiniauth\Test\Services\Security\AuthenticationHelper;
use Kinicart\Exception\Account\InsufficientBalanceException;
use Kinicart\Objects\Account\AccountBalance;
use Kinicart\Services\Account\AccountBalanceService;
use Kinicart\TestBase;
use Kinikit\Core\Configuration\Configuration;
use Kinikit\Core\DependencyInjection\Container;

include_once "autoloader.php";

class AccountBalanceServiceTest extends TestBase {

    /**
     * @var AccountBalanceService
     */
    private $accountBalanceService;

    public function setUp(): void {
        $this->accountBalanceService = Container::instance()->get(AccountBalanceService::class);
    }


    public function testCanTopUpBalanceForAccount() {

        AuthenticationHelper::login("admin@kinicart.com", "password");

        // Top up by amount in account currency
        $this->accountBalanceService->topUpBalance(15, null, 1);

        $balance = AccountBalance::fetch(1);
        $this->assertEquals(15, $balance->getBalance());
        $this->assertEquals("USD", $balance->getBalanceCurrencyCode());

        // Top up by amount in account currency
        $this->accountBalanceService->topUpBalance(7.25, null, 1);

        $balance = AccountBalance::fetch(1);
        $this->assertEquals(22.25, $balance->getBalance());
        $this->assertEquals("USD", $balance->getBalanceCurrencyCode());


        // Top up by amount in different currency
        $this->accountBalanceService->topUpBalance(10, "GBP", 1);

        $balance = AccountBalance::fetch(1);
        $this->assertEquals(34.25, $balance->getBalance());
        $this->assertEquals("USD", $balance->getBalanceCurrencyCode());

        // Top up by amount in different currency
        $this->accountBalanceService->topUpBalance(10, "EUR", 1);

        $balance = AccountBalance::fetch(1);
        $this->assertEquals(45.16, $balance->getBalance());
        $this->assertEquals("USD", $balance->getBalanceCurrencyCode());

    }


    public function testCanEnsureBalanceInAccountForAmount() {

        AuthenticationHelper::login("admin@kinicart.com", "password");

        $this->assertFalse($this->accountBalanceService->ensureBalance(5, "GBP", 2));
        $this->assertFalse($this->accountBalanceService->ensureBalance(5, "USD", 2));
        $this->assertFalse($this->accountBalanceService->ensureBalance(5, "EUR", 2));

        $this->accountBalanceService->topUpBalance(5, "USD", 2);

        $this->assertFalse($this->accountBalanceService->ensureBalance(5, "GBP", 2));
        $this->assertTrue($this->accountBalanceService->ensureBalance(5, "USD", 2));
        $this->assertFalse($this->accountBalanceService->ensureBalance(5, "EUR", 2));

        $this->accountBalanceService->topUpBalance(0.5, "GBP", 2);

        $this->assertFalse($this->accountBalanceService->ensureBalance(5, "GBP", 2));
        $this->assertTrue($this->accountBalanceService->ensureBalance(5, "USD", 2));
        $this->assertTrue($this->accountBalanceService->ensureBalance(5, "EUR", 2));

        $this->accountBalanceService->topUpBalance(0.5, "GBP", 2);

        $this->assertTrue($this->accountBalanceService->ensureBalance(5, "GBP", 2));
        $this->assertTrue($this->accountBalanceService->ensureBalance(5, "USD", 2));
        $this->assertTrue($this->accountBalanceService->ensureBalance(5, "EUR", 2));

    }


    public function testCanDeductFromBalance() {

        AuthenticationHelper::login("admin@kinicart.com", "password");

        $this->accountBalanceService->topUpBalance(3, "GBP", 3);

        // Deduct from balance
        $this->accountBalanceService->deductFromBalance(2, "GBP", 3);

        $balance = AccountBalance::fetch(3);
        $this->assertEquals(1, $balance->getBalance());
        $this->assertEquals("GBP", $balance->getBalanceCurrencyCode());

        // Deduct from balance
        $this->accountBalanceService->deductFromBalance(1, "USD", 3);

        $balance = AccountBalance::fetch(3);
        $this->assertEquals(0.17, $balance->getBalance());
        $this->assertEquals("GBP", $balance->getBalanceCurrencyCode());
    }

    public function testInsufficientBalanceExceptionRaisedIfAttemptToDeductWhereThereIsInsufficientBalance() {

        AuthenticationHelper::login("admin@kinicart.com", "password");

        try {
            $this->accountBalanceService->deductFromBalance(1, "GBP", 5);
            $this->fail("Should have thrown here");
        } catch (InsufficientBalanceException $e) {
            $this->assertTrue(true);
        }

    }

    public function testIfAccountBalancePrecisionConfiguredAccountBalanceIsMaintainedToHigherGranularity() {

        Configuration::instance()->addParameter("account.balance.precision", 4);


        AuthenticationHelper::login("admin@kinicart.com", "password");

        $this->accountBalanceService->topUpBalance(4.3213, "GBP", 4);

        $balance = AccountBalance::fetch(4);
        $this->assertEquals(4.3213, $balance->getBalance());

        $this->accountBalanceService->topUpBalance(0.0012, "GBP", 4);

        $balance = AccountBalance::fetch(4);
        $this->assertEquals(4.3225, $balance->getBalance());

        $this->accountBalanceService->deductFromBalance(0.0024, "GBP", 4);

        $balance = AccountBalance::fetch(4);
        $this->assertEquals(4.3201, $balance->getBalance());

    }

}