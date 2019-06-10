<?php

namespace Kinicart\Test;

use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Persistence\UPF\Framework\UPF;

class TestBase extends \PHPUnit\Framework\TestCase {

    public static function setUpBeforeClass() {
        include_once __DIR__ . "/TestDataInstaller.php";
    }

}
