<?php


namespace Kinicart\WebServices\Security;

use Kinikit\Core\DependencyInjection\ObjectInterceptor;
use Kinikit\Core\Util\HTTP\URLHelper;
use Kinikit\MVC\Framework\Controller;
use Kinikit\MVC\Framework\Controller\RESTService;


/**
 * Control access to controllers within the system.
 *
 * @package Kinicart\WebServices\Security
 */
abstract class ControllerAccessInterceptor extends ObjectInterceptor {


    /**
     * Before method interceptor for controller access.
     *
     * @param object $objectInstance
     * @param string $methodName
     * @param $params
     * @param \Kinikit\Core\Util\Annotation\ClassAnnotations $classAnnotations
     * @return string
     */
    public function beforeMethod($objectInstance, $methodName, $params, $classAnnotations) {
        if ($objectInstance instanceof Controller) {

            // Shortcut if this is handleRequest as there is no need to intercept this method.
            if (($objectInstance instanceof RESTService) && ($methodName == "handleRequest"))
                return $params;

            $this->onControllerAccess($objectInstance, $methodName, new URLHelper($_SERVER ['REQUEST_URI']));
        }
        return $params;
    }


    /**
     * On controller access method, called from above.
     *
     * @param Controller $controllerInstance
     * @param $methodName
     * @param URLHelper $urlHelper
     */
    public abstract function onControllerAccess($controllerInstance, $methodName, $urlHelper);


}
