<?php


namespace Kinicart\Test\TestData;


use DirectoryIterator;
use Kinicart\DB\DBInstaller;
use Kinikit\Core\Configuration;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Init;


class TestDataInstaller {


    /**
     * Run the db installer.  If core only is supplied, only the core kinicart schema
     * will be installed otherwise any custom application schema will also be run from the
     * local DB directory.
     */
    public function run($coreOnly = false, $installDB = true, $sourceDirectory = "../src", $testDirectory = ".") {

        if ($installDB) {
            $dbInstaller = new DBInstaller();
            $dbInstaller->run($coreOnly, $sourceDirectory);
        }

        // Initialise the application.
        Container::instance()->get(\Kinicart\Services\Application\BootstrapService::class);

        /**
         * @var $interceptor \Kinicart\Services\Security\ActiveRecordInterceptor
         */
        $interceptor = Container::instance()->get(\Kinicart\Services\Security\ActiveRecordInterceptor::class);


        $directories = array(array(__DIR__ . "/..", "Kinicart"));

        if (!$coreOnly) {
            $directories[] = array($testDirectory, Configuration::readParameter("application.namespace"));
        }

        $interceptor->executeInsecure(function () use ($directories) {

            foreach ($directories as list($directory, $namespace)) {
                if (file_exists($directory . "/TestData"))
                    $this->processTestDataDirectory($directory . "/TestData", $namespace);
            }

        });

    }


    // Install test data
    public static function runFromComposer($event) {

        new Init();

        $sourceDirectory = $event && isset($event->getComposer()->getPackage()->getConfig()["source-directory"]) ?
            $event->getComposer()->getPackage()->getConfig()["source-directory"] : ".";


        $testDirectory = $event && isset($event->getComposer()->getPackage()->getConfig()["test-directory"]) ?
            $event->getComposer()->getPackage()->getConfig()["test-directory"] : ".";

        $testDirectory = getcwd() . "/" . $testDirectory;

        chdir($sourceDirectory);

        $testDataInstaller = new TestDataInstaller();
        $testDataInstaller->run(false, true, ".", $testDirectory);

    }


    // Process test data directory looking for objects.
    private function processTestDataDirectory($directory, $baseNamespace) {

        $iterator = new DirectoryIterator($directory);
        $filepaths = array();
        foreach ($iterator as $item) {

            if ($item->isDot())
                continue;

            if ($item->isDir()) {
                $this->processTestDataDirectory($item->getRealPath(), $baseNamespace);
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

            if (is_numeric(strpos($targetClass[0], "Kinicart"))) {
                $targetClass = str_replace(array("/", "Kinicart"), array("\\", "Kinicart\\Objects"), $targetClass[0]);
            } else {
                $targetClass = $baseNamespace . "\\Objects\\" . str_replace("/", "\\", $targetClass[0]);
            }

            $items = json_decode(file_get_contents($filepath), true);

            $objects = \Kinikit\Core\Util\SerialisableArrayUtils::convertArrayToSerialisableObjects($items, $targetClass . "[]");

            foreach ($objects as $item) {
                $item->save();
            }

        }


    }

}


