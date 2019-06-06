<?php


namespace Kinicart\Services\Security;

use Kinicart\Objects\Application\Session;
use Kinikit\Core\Object\SerialisableObject;
use Kinikit\Persistence\UPF\Framework\UPFObjectInterceptorBase;

/**
 * Generic object interceptor for intercepting requests for all objects.  This predominently enforces security rules
 * around objects containing an accountId property.
 *
 */
class ObjectInterceptor extends UPFObjectInterceptorBase {

    private $authenticationService;
    private $session;

    private $loggedInUserId = null;
    private $loggedInAccountId = null;
    private $loggedInAuthorisedAccountIds = null;

    private $disabled = false;


    /**
     * @param \Kinicart\Services\Security\AuthenticationService $authenticationService
     * @param \Kinicart\Services\Application\Session $session
     */
    public function __construct($authenticationService, $session) {
        $this->authenticationService = $authenticationService;
        $this->session = $session;
    }


    public function postMap($object = null, $upfInstance = null) {
        return $this->disabled || $this->resolveAccessForObject($object);
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
    private function resolveAccessForObject($object) {

        $accessors = $object->__findSerialisablePropertyAccessors();

        // If an account id, check logged in items
        if (isset($accessors["accountid"])) {
            $accountId = $object->__getSerialisablePropertyValue("accountId");
            if (is_numeric($accountId)) {
                $accountPrivileges = $this->authenticationService->getLoggedInPrivileges($accountId);
                return sizeof($accountPrivileges) > 0;
            }
        }

        return true;

    }


}
