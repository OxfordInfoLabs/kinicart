<?php


namespace Kinicart\Objects\Application;

use Kinikit\MVC\Framework\SourceBaseManager;

/**
 * Generic bootstrap class - should be called early in application flow to ensure that global data is set up correctly.
 */
class Bootstrap {

    /**
     * @var $instance Bootstrap
     */
    private static $instance;

    // Block direct construction.
    private function __construct() {
    }


    /**
     * Static instance method in lieu of constructor.
     *
     * @return Bootstrap
     */
    public static function instance() {
        if (!self::$instance) {
            self::$instance = new Bootstrap();
        }

        return self::$instance;
    }


    /**
     * Run the bootstrapping logic.
     */
    public function run() {

        // Ensure kinicart is appended as a source base.
        SourceBaseManager::instance()->appendSourceBase(__DIR__ . "/../..");

        // Check the referring URL to see whether or not we need to update our logged in state.
        $splitReferrer = explode("//", $_SERVER["HTTP_REFERER"]);
        $referer = explode("/", $splitReferrer[1])[0];

        // If the referer differs from the session value, check some stuff.
        if ($referer !== Session::instance()->getReferringURL()) {
            Session::instance()->setReferringURL($referer);

            // Now attempt to look up the setting by key and value
            $setting = Setting::getByKeyAndValue("referringDomains", $referer);
            if ($setting) {
                $parentAccountId = $setting->getAccountId();
            } else {
                $parentAccountId = 0;
            }

            // Make sure we log out if the active parent account id has changed.
            if (Session::instance()->getActiveParentAccountId() != $parentAccountId)
                Authenticator::instance()->logOut();

            Session::instance()->setActiveParentAccountId($parentAccountId);


        }

    }

}
