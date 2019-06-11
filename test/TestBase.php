<?php

namespace Kinicart\Test;

use Kinicart\Test\TestData\TestDataInstaller;


class TestBase extends \PHPUnit\Framework\TestCase {

    private static $run = false;

    public static function setUpBeforeClass() {
        if (!self::$run) {
            $testDataInstaller = new TestDataInstaller();
            $testDataInstaller->run(true);
            self::$run = true;
        }
    }

}
