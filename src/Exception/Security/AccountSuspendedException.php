<?php


namespace Kinicart\Exception\Security;


class AccountSuspendedException extends \Exception {

    public function __construct() {
        parent::__construct("Your account has been suspended.");
    }

}
