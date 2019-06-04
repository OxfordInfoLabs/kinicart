<?php


namespace Kinicart\Services\Communication\Email\Provider;

use Kinicart\Objects\Communication\Email\Email;
use Kinicart\Objects\Communication\Email\EmailSendResult;

/**
 * Null provider which does nothing - default for testing and development.
 *
 * @package Kinicart\Objects\Communication\Email\Transport
 */
class NullProvider extends EmailProvider {

    /**
     * Send an email.
     *
     * @param Email $email
     *
     * @return \Kinicart\Objects\Communication\Email\EmailSendResult
     *
     */
    public function send($email) {
        return new EmailSendResult(Email::STATUS_SENT);
    }
}
