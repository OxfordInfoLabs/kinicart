<?php

namespace Kinicart;

use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Init;
use Kinikit\Persistence\Tools\TestDataInstaller;

class TestBase extends \PHPUnit\Framework\TestCase {

    private static $run = false;

    public static function setUpBeforeClass(): void {

        $bootstrap = new Bootstrap();
        $bootstrap->setup();

        if (!self::$run) {
            $testDataInstaller = Container::instance()->get(TestDataInstaller::class);
            $testDataInstaller->run(true);

            Container::instance()->get(Init::class);

            self::$run = true;
        }
    }

}
