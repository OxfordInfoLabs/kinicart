<?php


namespace Kinicart\SQL;


use DirectoryIterator;
use Kinikit\Persistence\Database\Connection\DefaultDB;

class DBInstaller {

    /**
     * Main clean function.
     */
    public static function clean($event) {

        $sourceDirectory = $event && isset($event->getComposer()->getPackage()->getConfig()["source-directory"]) ?
            $event->getComposer()->getPackage()->getConfig()["source-directory"] : ".";

        chdir($sourceDirectory);

        $databaseConnection = DefaultDB::instance();

        $directoryIterator = new DirectoryIterator(__DIR__);
        foreach ($directoryIterator as $item) {
            if ($item->isDot()) continue;
            if ($item->getExtension() != "sql") continue;
            $databaseConnection->executeScript(file_get_contents($item->getRealPath()));
        }

    }

}
