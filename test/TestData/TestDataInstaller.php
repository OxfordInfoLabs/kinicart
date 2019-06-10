<?php


namespace Kinicart\Test\TestData;


use DirectoryIterator;
use Kinicart\SQL\DBInstaller;
use Kinikit\Core\DependencyInjection\Container;


class TestDataInstaller {


    // Install test data
    public static function install($event) {

        // Run db installer
        DBInstaller::clean($event);


        // Initialise the application.
        Container::instance()->get(\Kinicart\Services\Application\BootstrapService::class);


        /**
         * @var $interceptor \Kinicart\Services\Security\ActiveRecordInterceptor
         */
        $interceptor = Container::instance()->get(\Kinicart\Services\Security\ActiveRecordInterceptor::class);


        $interceptor->executeInsecure(function () {

            self::processTestDataDirectory(__DIR__);

        });

    }


    // Process test data directory looking for objects.
    private static function processTestDataDirectory($directory) {

        $iterator = new DirectoryIterator($directory);
        $filepaths = array();
        foreach ($iterator as $item) {

            if ($item->isDot())
                continue;

            if ($item->isDir()) {
                self::processTestDataDirectory($item->getRealPath());
                continue;
            }

            if ($item->getExtension() != "json")
                continue;

            $filepaths[] = $item->getRealPath();


        }

        sort($filepaths);

        foreach ($filepaths as $filepath) {

            // Now grab the filename and explode on TestData
            $exploded = explode("TestData/", $filepath);
            $targetClass = explode(".", $exploded[1]);
            $targetClass = "Kinicart\\Objects\\" . str_replace("/", "\\", $targetClass[0]);

            $items = json_decode(file_get_contents($filepath), true);

            $objects = \Kinikit\Core\Util\SerialisableArrayUtils::convertArrayToSerialisableObjects($items, $targetClass . "[]");

            foreach ($objects as $item) {
                $item->save();
            }

        }


    }

}


