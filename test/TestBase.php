<?php

namespace Kinicart;

use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Bootstrapper;
use Kinikit\Persistence\Tools\TestDataInstaller;

class TestBase extends \PHPUnit\Framework\TestCase {

    private static $run = false;

    public static function setUpBeforeClass(): void {

        $bootstrap = new Bootstrap();
        $bootstrap->setup();

        if (!self::$run) {
            $testDataInstaller = Container::instance()->get(TestDataInstaller::class);
            $testDataInstaller->run(true);

            Container::instance()->get(Bootstrapper::class);

            self::$run = true;
        }
    }

}
