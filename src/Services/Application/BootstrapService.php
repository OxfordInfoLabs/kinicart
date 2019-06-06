<?php


namespace Kinicart\Services\Application;

use Kinicart\Objects\Application\Session;
use Kinicart\Services\Security\MethodInterceptor;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\MVC\Framework\SourceBaseManager;
use Kinikit\Persistence\UPF\Framework\UPF;

/**
 * Generic bootstrap class - should be called early in application flow to ensure that global data is set up correctly.
 */
class BootstrapService {

    private $authenticationService;


    /**
     * Construct with authentication service
     *
     * @param \Kinicart\Services\Security\AuthenticationService $authenticationService
     *
     */
    public function __construct($authenticationService) {

        $this->authenticationService = $authenticationService;
        $this->run();

    }


    /**
     * Run the bootstrapping logic.
     */
    private function run() {

        // Ensure kinicart is appended as a source base.
        SourceBaseManager::instance()->appendSourceBase(__DIR__ . "/../src");

        // Add the kinicart UPF file for formatters etc.
        UPF::instance()->getPersistenceCoordinator()->setIncludedMappingFiles(__DIR__ . "/../../Config/upf.xml");

        // Add the object interceptor
        $objectInterceptor = Container::instance()->get("Kinicart\Services\Security\ObjectInterceptor");
        UPF::instance()->getPersistenceCoordinator()->setInterceptors(array($objectInterceptor));

        // Add the method interceptor
        Container::instance()->addMethodInterceptor(new MethodInterceptor($objectInterceptor));

        // Update the active parent account using the HTTP Referer.
        $this->authenticationService->updateActiveParentAccount(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "");

    }

}
