<?php


namespace Kinicart\WebServices\Security;

use Kinicart\Exception\Security\AccessDeniedException;
use Kinicart\Exception\Security\MissingAPICredentialsException;
use Kinicart\Services\Security\AuthenticationService;
use Kinicart\Services\Security\SecurityService;
use Kinikit\Core\Util\HTTP\HttpRequest;
use Kinikit\Core\Util\HTTP\URLHelper;
use Kinikit\MVC\Framework\Controller;

/**
 * Default controller method interceptor.  This implements rules based upon the calling path to a controller using
 * the convention
 *
 * @package Kinicart\WebServices\Security
 */
class DefaultControllerAccessInterceptor extends ControllerAccessInterceptor {

    private $securityService;
    private $authenticationService;

    /**
     * Constructor
     *
     * @param SecurityService $securityService
     * @param AuthenticationService $authenticationService
     */
    public function __construct($securityService, $authenticationService) {
        $this->securityService = $securityService;
        $this->authenticationService = $authenticationService;
    }


    /**
     * On controller access method, called from above.
     *
     * @param Controller $controllerInstance
     * @param URLHelper $urlHelper
     */
    public function onControllerAccess($controllerInstance, $urlHelper) {
        $controlSegment = $urlHelper->getFirstSegment();

        list($user, $account) = $this->securityService->getLoggedInUserAndAccount();

        // If customer segment, make sure at least someone is logged in.
        if ($controlSegment == "customer") {
            if (!$user && !$account)
                throw new AccessDeniedException();
        } else if ($controlSegment == "admin") {
            if (!$this->securityService->isSuperUserLoggedIn())
                throw new AccessDeniedException();
        } else if ($controlSegment == "api") {
            $apiKey = HttpRequest::instance()->getParameter("apiKey");
            $apiSecret = HttpRequest::instance()->getParameter("apiSecret");
            if (!$apiKey || !$apiSecret) {
                throw new MissingAPICredentialsException();
            }
            if (!$account || $account->getApiKey() != $apiKey) {
                $this->authenticationService->apiAuthenticate($apiKey, $apiSecret);
            }
        }


    }
}
