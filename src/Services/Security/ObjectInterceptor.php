<?php


namespace Kinicart\Services\Security;


use Kinicart\Exception\Security\AccessDeniedException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Security\Role;
use Kinicart\Objects\Security\User;
use Kinicart\Services\Application\Session;

/**
 * Generic method interceptor.  Currently allows for privilege based enforcement at the method level as well
 * as an ability to override object interceptors using markup.
 */
class ObjectInterceptor extends \Kinikit\Core\DependencyInjection\ObjectInterceptor {

    private $objectInterceptor;
    private $securityService;


    /**
     * @param \Kinicart\Services\Security\ActiveRecordInterceptor $objectInterceptor
     * @param \Kinicart\Services\Security\SecurityService $securityService
     */
    public function __construct($objectInterceptor, $securityService) {
        $this->objectInterceptor = $objectInterceptor;
        $this->securityService = $securityService;
    }


    /**
     * Check for privileges before we allow the method to be executed.
     * Also, allow for plugging in of logged in data as default data if required.
     *
     * @param object $objectInstance - The object being called
     * @param string $methodName - The method name
     * @param string[string] $params - The parameters passed to the method as name => value pairs.
     * @param \Kinikit\Core\Util\Annotation\ClassAnnotations $classAnnotations - The class annotations for convenience for e.g. role based enforcement.
     *
     * @return string[string] - The params array either intact or modified if required.
     */
    public function beforeMethod($objectInstance, $methodName, $params, $classAnnotations) {

        if ($matches = $classAnnotations->getMethodAnnotationsForMatchingTag("hasPrivilege", $methodName)) {

            foreach ($matches as $match) {

                // Work out which scenario we are in - implicit account or explicit parameter.
                $matchValue = $match->getValue();
                preg_match("/(.+)\((.+)\)/", $matchValue, $matches);

                if ($matches && sizeof($matches) == 3) {

                    $privilegeKey = trim($matches[1]);
                    $paramName = ltrim($matches[2], "$");

                    // Locate the parameter in the method signature
                    $scopeId = isset($params[$paramName]) ? $params[$paramName] : null;

                } else {
                    $privilegeKey = trim($matchValue);
                    $scopeId = null;
                }

                // Throw if an issue is encountered.
                if (!$this->securityService->checkLoggedInHasPrivilege($privilegeKey, $scopeId))
                    throw new AccessDeniedException();


            }
        }


        if ($key = array_search(Account::LOGGED_IN_ACCOUNT, $params)) {
            list($user, $account) = $this->securityService->getLoggedInUserAndAccount();
            if ($account) {
                $params[$key] = $account->getAccountId();
            } else {
                $params[$key] = null;
            }
        }

        if ($key = array_search(User::LOGGED_IN_USER, $params)) {
            list($user, $account) = $this->securityService->getLoggedInUserAndAccount();
            if ($user) {
                $params[$key] = $user->getId();
            } else {
                $params[$key] = null;
            }
        }

        return $params;


    }


    /**
     * Check for object interceptor disabling.
     *
     * @param callable $callable
     * @param \Kinikit\Core\Util\Annotation\ClassAnnotations $classAnnotations
     *
     * @return callable
     */
    public function methodCallable($callable, $methodName, $params, $classAnnotations) {

        // Check for objectInterceptorDisabled
        if ($classAnnotations->getMethodAnnotationsForMatchingTag("objectInterceptorDisabled", $methodName)) {
            return function () use ($callable) {
                return $this->objectInterceptor->executeInsecure($callable);
            };
        }

        return $callable;
    }


}
