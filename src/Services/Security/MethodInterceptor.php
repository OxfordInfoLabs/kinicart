<?php


namespace Kinicart\Services\Security;


use Kinicart\Exception\Security\AccessDeniedException;
use Kinicart\Objects\Security\Role;
use Kinicart\Services\Application\Session;

/**
 * Generic method interceptor.  Currently allows for privilege based enforcement at the method level as well
 * as an ability to override object interceptors using markup.
 */
class MethodInterceptor extends \Kinikit\Core\DependencyInjection\MethodInterceptor {

    private $objectInterceptor;
    private $securityService;


    /**
     * @param \Kinicart\Services\Security\ObjectInterceptor $objectInterceptor
     * @param \Kinicart\Services\Security\SecurityService $securityService
     */
    public function __construct($objectInterceptor, $securityService) {
        $this->objectInterceptor = $objectInterceptor;
        $this->securityService = $securityService;
    }


    /**
     * Check privileges before permitting method to execute.
     *
     * @param object $objectInstance
     * @param string $methodName
     * @param array $params
     * @param \Kinikit\Core\Util\Annotation\ClassAnnotations $classAnnotations
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
                    $reflectionClass = new \ReflectionClass($objectInstance);
                    $method = $reflectionClass->getMethod($methodName);
                    $methodParams = $method->getParameters();
                    foreach ($methodParams as $index => $param) {
                        if ($param->getName() == $paramName) {
                            $scopeId = $params[$index];
                            break;
                        }
                    }

                } else {
                    $privilegeKey = trim($matchValue);
                    $scopeId = null;
                }

                // Throw if an issue is encountered.
                if (!$this->securityService->checkLoggedInHasPrivilege($privilegeKey, $scopeId))
                    throw new AccessDeniedException();


            }
        }

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
