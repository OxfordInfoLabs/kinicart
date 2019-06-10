<?php


namespace Kinicart\Services\Security;

use Kinicart\Exception\Security\AccessDeniedException;
use Kinicart\Objects\Application\Session;
use Kinikit\Core\Object\SerialisableObject;
use Kinikit\Persistence\UPF\Framework\UPFObjectInterceptorBase;

/**
 * Generic object interceptor for intercepting requests for all objects.  This predominently enforces security rules
 * around objects containing an accountId property.
 *
 */
class ObjectInterceptor extends UPFObjectInterceptorBase {

    private $securityService;
    private $session;

    private $disabled = false;


    /**
     * @param \Kinicart\Services\Security\SecurityService $securityService
     * @param \Kinicart\Services\Application\Session $session
     */
    public function __construct($securityService, $session) {
        $this->securityService = $securityService;
        $this->session = $session;
    }


    public function postMap($object = null, $upfInstance = null) {
        return $this->disabled || $this->resolveAccessForObject($object, false);
    }

    public function preSave($object = null, $upfInstance = null) {
        return $this->disabled || $this->resolveAccessForObject($object);
    }

    public function preDelete($object = null, $upfInstance = null) {
        return $this->disabled || $this->resolveAccessForObject($object);
    }


    /**
     * Execute a callable block insecurely with interceptors disabled.
     *
     * @param callable $callable
     */
    public function executeInsecure($callable) {

        // Disable for the duration of the callable
        $this->disabled = true;

        // Run the callable
        try {
            $result = $callable();
        } catch (\Throwable $e) {
            $this->disabled = false;
            throw($e);
        }

        $this->disabled = false;

        return $result;
    }


    /**
     * @param SerialisableObject $object
     * @return bool
     */
    private function resolveAccessForObject($object, $throwException = true) {
        if ($this->securityService->checkLoggedInObjectAccess($object))
            return true;
        else {
            if ($throwException)
                throw new AccessDeniedException();
            else
                return false;
        }
    }


}
