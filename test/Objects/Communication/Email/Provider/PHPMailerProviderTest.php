<?php

namespace Kinicart\Test\Objects\Communication\Email\Provider;

use Kinicart\Objects\Communication\Email\Email;
use Kinicart\Objects\Communication\Email\Provider\PHPMailerProvider;
use Kinicart\Test\TestBase;


include_once __DIR__ . "/../../../../autoloader.php";

class PHPMailerProviderTest extends TestBase {

    public function testCanSendBasicEmailUsingPHPMailer() {

        $phpMailer = new PHPMailerProvider("localhost", 25);

        // Send simple email
        $email = new Email("Kinicart Test <test@kinicart.com>", "Mark Oxil <mark@oxil.co.uk>",
            "Test Email using PHP Mailer", "This is a little test to confirm that email is going out correctly");

        $phpMailer->send($email);

        $this->assertEquals(Email::STATUS_SENT, $email->getStatus());


        // Send email with CC, BCC, custom Reply to and attachments
        $email = new Email("Kinicart Test <test@kinicart.com>", "Mark Oxil <mark@oxil.co.uk>",
            "Test Email using PHP Mailer with Attachments", "This is a more advanced test to ensure that email is 
            going out as expected", array("Mark CC 1 <mark+cc1@oxil.co.uk>", "Mark CC 2 <mark+cc2@oxil.co.uk>"),
            array("Mark BCC 1 <mark+bcc1@oxil.co.uk>", "Mark BCC 2 <mark+bcc2@oxil.co.uk>"),
            "Marky Mark and Funky Bunch <mark+replyto@oxil.co.uk>");

        $email->setLocalAttachmentFiles(array(__DIR__ . "/testtext.txt", __DIR__ . "/testimage.png"));


        $phpMailer->send($email);

        $this->assertEquals(Email::STATUS_SENT, $email->getStatus());

    }


}
