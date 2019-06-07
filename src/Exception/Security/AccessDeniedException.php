<?php


namespace Kinicart\Exception\Security;


use Kinikit\Core\Exception\SerialisableException;
use Throwable;

/**
 * Generic access denied exception.  Raised if there is an issue accessing a method or object in the system.
 *
 * @package Kinicart\Exception\Security
 */
class AccessDeniedException extends SerialisableException {

    public function __construct() {
        parent::__construct("You do not have sufficient privileges to complete the requested operation");
    }

}
