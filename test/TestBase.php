<?php

namespace Kinicart\Test;

class TestBase extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        include_once __DIR__ . "/InstallTestData.php";
    }

}
