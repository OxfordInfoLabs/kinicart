<?php

namespace Kinicart;

use Kinikit\Core\Configuration\Configuration;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Bootstrapper;
use Kinikit\Persistence\Tools\TestDataInstaller;

class TestBase extends \PHPUnit\Framework\TestCase {

    private static $run = false;

    public static function setUpBeforeClass(): void {

        $bootstrap = new Bootstrap();
        $bootstrap->setup();

        if (!self::$run) {
            $dbPath = __DIR__ . "/" . Configuration::readParameter("db.filename");
            if (file_exists($dbPath))
                unlink($dbPath);

            $testDataInstaller = Container::instance()->get(TestDataInstaller::class);
            $testDataInstaller->run(true);

            Container::instance()->get(Bootstrapper::class);

            self::$run = true;
        }
    }

}
