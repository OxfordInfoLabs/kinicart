<?php


namespace Kinicart\Objects\Communication\Email\Provider;

/**
 * Null provider which does nothing - default for testing and development.
 *
 * @package Kinicart\Objects\Communication\Email\Transport
 */
class NullProvider extends EmailProvider {

    /**
     * Send an email.
     *
     * @param $email
     *
     */
    public function send($email) {
        // DO NOTHING
    }
}
