<?php


namespace Kinicart\Exception\Account;


class InsufficientBalanceException extends \Exception {

    public function __construct() {
        parent::__construct("You have insufficient balance in your account for the specified operation");
    }

}