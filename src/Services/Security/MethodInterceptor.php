<?php


namespace Kinicart\Services\Security;


/**
 * Generic method interceptor.  Currently allows for privilege based enforcement at the method level as well
 * as an ability to override object interceptors using markup.
 */
class MethodInterceptor extends \Kinikit\Core\DependencyInjection\MethodInterceptor {

    private $objectInterceptor;


    /**
     * @param \Kinicart\Services\Application\ObjectInterceptor $objectInterceptor
     */
    public function __construct($objectInterceptor) {
        $this->objectInterceptor = $objectInterceptor;
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
