<?php

namespace Kinicart;

use Kiniauth\Objects\Account\Account;
use Kiniauth\Services\Application\SessionData;
use Kinikit\Core\ApplicationBootstrap;
use Kinikit\Core\DependencyInjection\Container;

class Bootstrap implements ApplicationBootstrap {


    /**
     * Set up logic, run on each request, first before any request processing.
     *
     */
    public function setup() {

        // Map the core account class to the kinicart one with extra data.
        Container::instance()->addClassMapping(Account::class, \Kinicart\Objects\Account\Account::class);
        Container::instance()->addClassMapping(SessionData::class, \Kinicart\Services\Application\SessionData::class);

    }
}
