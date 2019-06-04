<?php


namespace Kinicart\Services\Application;

use Kinicart\Objects\Application\Session;
use Kinikit\MVC\Framework\SourceBaseManager;

/**
 * Generic bootstrap class - should be called early in application flow to ensure that global data is set up correctly.
 */
class BootstrapService {

    private $authenticationService;
    private $settingsService;


    /**
     * Construct with authentication service
     *
     * @param \Kinicart\Services\Application\AuthenticationService $authenticationService
     * @param \Kinicart\Services\Application\SettingsService $settingsService
     *
     */
    public function __construct($authenticationService, $settingsService) {
        $this->authenticationService = $authenticationService;
        $this->settingsService = $settingsService;
    }


    /**
     * Run the bootstrapping logic.
     */
    public function run() {

        // Ensure kinicart is appended as a source base.
        SourceBaseManager::instance()->appendSourceBase(__DIR__ . "/../src");

        // Check the referring URL to see whether or not we need to update our logged in state.
        $splitReferrer = explode("//", $_SERVER["HTTP_REFERER"]);
        $referer = explode("/", $splitReferrer[1])[0];

        // If the referer differs from the session value, check some stuff.
        if ($referer !== Session::instance()->getReferringURL()) {
            Session::instance()->setReferringURL($referer);

            // Now attempt to look up the setting by key and value
            $setting = $this->settingsService->getSettingByKeyAndValue("referringDomains", $referer);
            if ($setting) {
                $parentAccountId = $setting->getAccountId();
            } else {
                $parentAccountId = 0;
            }

            // Make sure we log out if the active parent account id has changed.
            if (Session::instance()->getActiveParentAccountId() != $parentAccountId) {
                $this->authenticationService->logOut();
            }

            Session::instance()->setActiveParentAccountId($parentAccountId);


        }

    }

}
