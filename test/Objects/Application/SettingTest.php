<?php


namespace Kinicart\Test\Objects\Application;


use Kinicart\Objects\Application\Setting;
use Kinicart\Test\TestBase;
use Kinikit\MVC\Framework\SourceBaseManager;

include_once __DIR__ . "/../../autoloader.php";


class SettingTest extends TestBase {


    /**
     * Set up for this - needs to add in the Source Base
     */
    public function setUp():void {
        SourceBaseManager::instance()->appendSourceBase(__DIR__ . "/../../../src");
    }


    public function testCanGetAllSettingDefinitions() {

        $allDefs = Setting::getSettingDefinitions();

        $this->assertNotNull($allDefs["brandName"]);
        $this->assertEquals("Site / Brand Name", $allDefs["brandName"]["title"]);

    }


    public function testDefinitionFieldsAttachedWhenRetrievingSettingsInAnyWay() {

        $setting = Setting::fetch(array(1, "brandName", 0));
        $this->assertEquals("brandName", $setting->getKey());
        $this->assertNotNull($setting->getTitle());
        $this->assertNotNull($setting->getDescription());


    }


}
