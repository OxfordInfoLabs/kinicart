<?php

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


// Add core test data
echo "\n\nAdding core test data.....";
processTestDataDirectory(__DIR__ . "/TestData");


// Process test data directory looking for objects.
function processTestDataDirectory($directory) {

    $iterator = new DirectoryIterator($directory);
    foreach ($iterator as $item) {

        if ($item->isDot())
            continue;

        if ($item->isDir()) {
            processTestDataDirectory($item->getRealPath());
            continue;
        }

        // Now grab the filename and explode on TestData
        $exploded = explode("TestData/", $item->getRealPath());
        $targetClass = explode(".", $exploded[1]);
        $targetClass = "Kinicart\\Objects\\" . str_replace("/", "\\", $targetClass[0]);

        $items = json_decode(file_get_contents($item->getRealPath()), true);

        foreach ($items as $item) {

            /**
             * @var $obj ActiveRecord
             */
            $obj = new $targetClass();
            $obj->bind($item);
            $obj->save();

        }


    }


}

