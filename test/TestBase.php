<?php

namespace Kinicart\Test;

use Kinicart\Test\TestData\TestDataInstaller;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Persistence\UPF\Framework\UPF;

class TestBase extends \PHPUnit\Framework\TestCase {

    public static function setUpBeforeClass() {
        TestDataInstaller::install(null);
    }

}
