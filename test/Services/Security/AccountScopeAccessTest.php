<?php


namespace Kinicart\Test\Services\Security;


use Kinicart\Objects\Account\AccountSummary;
use Kinicart\Objects\Security\Privilege;
use Kinicart\Objects\Security\User;
use Kinicart\Services\Security\AccountScopeAccess;
use Kinicart\Services\Security\AuthenticationService;
use Kinicart\Test\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once __DIR__ . "/../../autoloader.php";

class AccountScopeAccessTest extends TestBase {

    /**
     * @var AccountScopeAccess
     */
    private $accountScopeAccess;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    public function setUp() {
        parent::setUp();
        $this->accountScopeAccess = new AccountScopeAccess();
        $this->authenticationService = Container::instance()->get(AuthenticationService::class);

        $this->authenticationService->login("admin@kinicart.com", "password");
    }


    public function testCanGenerateAccountScopePrivilegesFromUserRoles() {

        // Super user
        $user = User::fetch(1);
        $privileges = $this->accountScopeAccess->generateScopePrivileges($user, null, null);
        $this->assertEquals(array("*" => ["*"]), $privileges);


        // Account admin
        $user = User::fetch(2);
        $privileges = $this->accountScopeAccess->generateScopePrivileges($user, null, null);
        $this->assertEquals(["*"], $privileges[1]);

        // User with dual account admin access.
        $user = User::fetch(7);
        $privileges = $this->accountScopeAccess->generateScopePrivileges($user, null, null);
        $this->assertFalse(isset($privileges[1]));
        $this->assertEquals(["viewdata", "editdata", "deletedata"], $privileges[2]);
        $this->assertFalse(isset($privileges[3]));
        $this->assertEquals(["viewdata", "editdata"], $privileges[4]);

        // User with sub accounts.
        $user = User::fetch(2);
        $privileges = $this->accountScopeAccess->generateScopePrivileges($user, null, null);
        $this->assertEquals(["*"], $privileges[5]);
        $this->assertEquals(["*"], $privileges[9]);


        // Account logged in by API
        $account = AccountSummary::fetch(1);
        $privileges = $this->accountScopeAccess->generateScopePrivileges(null, $account, null);
        $this->assertEquals(["*"], $privileges[1]);
        $this->assertEquals(["*"], $privileges[5]);
        $this->assertEquals(["*"], $privileges[9]);


    }


}
