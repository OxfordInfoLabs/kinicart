<?php

// Call the core autoloader.
include_once __DIR__ . "/../vendor/autoload.php";

/**
 * Test autoloader - includes src one as well.
 */
spl_autoload_register(function ($class) {

    // Check for test classes first
    $testClass = str_replace("Kinicart\\Test\\", "", $class);
    if ($testClass !== $class) {
        $file = DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        if (file_exists(__DIR__ . $file)) {
            require __DIR__ . $file;
            return true;
        }
    }

    // Now check for source classes.
    $srcClass = str_replace("Kinicart\\", "", $class);
    if ($srcClass !== $class) {
        $file = DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        if (file_exists(__DIR__ . "/../src$file")) {
            require __DIR__ . $file;
            return true;
        }
    }


    return false;

});
