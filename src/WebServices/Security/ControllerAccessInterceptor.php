<?php


namespace Kinicart\WebServices\Security;

use Kinikit\Core\DependencyInjection\ObjectInterceptor;
use Kinikit\Core\Util\HTTP\URLHelper;
use Kinikit\MVC\Framework\Controller;


/**
 * Control access to controllers within the system.
 *
 * @package Kinicart\WebServices\Security
 */
abstract class ControllerAccessInterceptor extends ObjectInterceptor {


    /**
     * Intercept after create to provide any cross cutting controller logic.
     *
     * @param $objectInstance
     */
    public function afterCreate($objectInstance) {
        if ($objectInstance instanceof Controller) {
            $this->onControllerAccess($objectInstance, URLHelper::getCurrentURLInstance());
        }
    }


    /**
     * On controller access method, called from above.
     *
     * @param Controller $controllerInstance
     * @param URLHelper $urlHelper
     */
    public abstract function onControllerAccess($controllerInstance, $urlHelper);


}
