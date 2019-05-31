<?php


namespace Kinicart\Objects\Communication\Email\Provider;


use Kinikit\Core\Configuration;

/**
 * Base email transport class.  Only one method required to be implemented (send)
 */
abstract class EmailProvider {

    /**
     * Send an email.
     *
     * @param $email
     *
     */
    public abstract function send($email);


    /**
     * Get the active transport in use
     */
    public static function getActiveProvider() {

        switch (Configuration::readParameter("email.provider")) {
            case "phpmailer":

                break;

            default:
                return NullProvider::instance();

        }

    }

}
