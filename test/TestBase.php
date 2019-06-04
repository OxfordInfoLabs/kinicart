<?php

namespace Kinicart\Test;

use Kinikit\Persistence\UPF\Framework\UPF;

class TestBase extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        include_once __DIR__ . "/InstallTestData.php";

        UPF::instance()->getPersistenceCoordinator()->setIncludedMappingFiles(__DIR__ . "/../src/Config/upf.xml");

    }

}
