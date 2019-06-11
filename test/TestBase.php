<?php

namespace Kinicart\Test;

use Kinicart\Test\TestData\TestDataInstaller;


class TestBase extends \PHPUnit\Framework\TestCase {

    public static function setUpBeforeClass() {
        $testDataInstaller = new TestDataInstaller();
        $testDataInstaller->run(true);
    }

}
