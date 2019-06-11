<?php


namespace Kinicart\DB;


use DirectoryIterator;
use Kinikit\Persistence\Database\Connection\DefaultDB;

class DBInstaller {

    /**
     * Run the db installer.  If core only is supplied, only the core kinicart schema
     * will be installed otherwise any custom application schema will also be installed from the
     * application source directory which defaults to the current directory.
     */
    public function run($coreOnly = false, $sourceDirectory = ".") {

        $databaseConnection = DefaultDB::instance();

        $directories = array(__DIR__);
        if (!$coreOnly) $directories[] = $sourceDirectory;

        // Run core (and application) DB installs
        foreach ($directories as $directory) {
            $directoryIterator = new DirectoryIterator($directory);
            foreach ($directoryIterator as $item) {
                if ($item->isDot()) continue;
                if ($item->getExtension() != "sql") continue;
                $databaseConnection->executeScript(file_get_contents($item->getRealPath()));
            }
        }
    }


    /**
     * Main clean function.
     */
    public static function runFromComposer($event) {

        $sourceDirectory = $event && isset($event->getComposer()->getPackage()->getConfig()["source-directory"]) ?
            $event->getComposer()->getPackage()->getConfig()["source-directory"] : ".";

        chdir($sourceDirectory);

        $installer = new DBInstaller();
        $installer->run();


    }

}
