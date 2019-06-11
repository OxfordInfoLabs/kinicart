<?php


namespace Kinicart\Services\Application;

use Kinicart\Services\Application\Session;
use Kinicart\Services\Security\ObjectInterceptor;
use Kinicart\Services\Security\ActiveRecordInterceptor;
use Kinicart\Services\Security\SecurityService;
use Kinicart\WebServices\Security\DefaultControllerAccessInterceptor;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\MVC\Framework\SourceBaseManager;
use Kinikit\Persistence\UPF\Framework\UPF;

/**
 * Generic bootstrap class - should be called early in application flow to ensure that global data is set up correctly.
 */
class BootstrapService {

    private $authenticationService;
    private $activeRecordInterceptor;
    private $securityService;


    /**
     * Construct with authentication service
     *
     * @param \Kinicart\Services\Security\AuthenticationService $authenticationService
     * @param \Kinicart\Services\Security\ActiveRecordInterceptor $activeRecordInterceptor
     * @param \Kinicart\Services\Security\SecurityService $securityService
     *
     */
    public function __construct($authenticationService, $activeRecordInterceptor, $securityService) {

        $this->authenticationService = $authenticationService;
        $this->activeRecordInterceptor = $activeRecordInterceptor;
        $this->securityService = $securityService;
        $this->run();

    }


    /**
     * Run the bootstrapping logic.
     */
    private function run() {

        // Ensure kinicart is appended as a source base.
        SourceBaseManager::instance()->appendSourceBase(__DIR__ . "/../..");

        // Add the kinicart UPF file for formatters etc.
        UPF::instance()->getPersistenceCoordinator()->setIncludedMappingFiles(__DIR__ . "/../../Config/upf.xml");

        // Add the object interceptor
        UPF::instance()->getPersistenceCoordinator()->setInterceptors(array($this->activeRecordInterceptor));

        // Add the generic object method interceptor
        Container::instance()->addMethodInterceptor(new ObjectInterceptor($this->activeRecordInterceptor, $this->securityService));

        // Add the controller method interceptor
        Container::instance()->addMethodInterceptor(new DefaultControllerAccessInterceptor($this->securityService, $this->authenticationService));
        
        // Update the active parent account using the HTTP Referer.
        $this->authenticationService->updateActiveParentAccount(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "");

    }

}
