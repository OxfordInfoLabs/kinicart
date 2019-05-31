<?php


namespace Kinicart\Exception\Application;


class InvalidAPICredentialsException extends \Exception {

    public function __construct() {
        parent::__construct("The api key or secret supplied was invalid");
    }

}
