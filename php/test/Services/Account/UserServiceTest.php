<?php

namespace Kinicart\Test\Services\Account;

use Kiniauth\Services\Account\UserService;
use Kiniauth\Services\Security\SecurityService;
use Kiniauth\Test\Services\Security\AuthenticationHelper;
use Kinicart\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once "autoloader.php";

class UserServiceTest extends TestBase {

    public function testSwitchingAccountCorrectlyAppliesNewAccountData() {

        $securityService = Container::instance()->get(SecurityService::class);
        $userService = Container::instance()->get(UserService::class);

        AuthenticationHelper::login("james@smartcoasting.org", "password");

        [$loggedInUser, $loggedInAccount] = $securityService->getLoggedInSecurableAndAccount();

        $this->assertEquals(3, $loggedInAccount->getAccountId());
        $this->assertEquals(1, $loggedInAccount->getAccountData()->getTierId());


        // Switch account
        $userService->switchActiveAccount(2);


        [$loggedInUser, $loggedInAccount] = $securityService->getLoggedInSecurableAndAccount();

        $this->assertEquals(2, $loggedInAccount->getAccountId());
        $this->assertEquals(2, $loggedInAccount->getAccountData()->getTierId());


    }

}