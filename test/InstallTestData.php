<?php

use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Persistence\Database\Connection\DefaultDB;
use Kinikit\Persistence\UPF\Object\ActiveRecord;

include_once __DIR__ . "/autoloader.php";

// Install core schemas first
echo "\nInitialising database with core schemas.....";

if (file_exists("DB/application.db"))
    unlink("DB/application.db");


$databaseConnection = DefaultDB::instance();

$directoryIterator = new DirectoryIterator(__DIR__ . "/../src/SQL");
foreach ($directoryIterator as $item) {
    if ($item->isDot()) continue;
    $databaseConnection->executeScript(file_get_contents($item->getRealPath()));
}


// Initialise the application.
Container::instance()->get(\Kinicart\Services\Application\BootstrapService::class);

/**
 * @var $interceptor \Kinicart\Services\Security\ActiveRecordInterceptor
 */
$interceptor = Container::instance()->get(\Kinicart\Services\Security\ActiveRecordInterceptor::class);

$interceptor->executeInsecure(function () {

// Add core test data
    echo "\n\nAdding core test data.....";
    processTestDataDirectory(__DIR__ . "/TestData");

});


// Process test data directory looking for objects.
function processTestDataDirectory($directory) {

    $iterator = new DirectoryIterator($directory);
    $filepaths = array();
    foreach ($iterator as $item) {

        if ($item->isDot())
            continue;

        if ($item->isDir()) {
            processTestDataDirectory($item->getRealPath());
            continue;
        }

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

