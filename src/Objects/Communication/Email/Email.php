<?php

namespace Kinicart\Objects\Communication\Email;


use Kinicart\Objects\Communication\Attachment\Attachment;
use Kinikit\Persistence\UPF\Object\ActiveRecord;

/**
 *
 * @ormTable kc_email
 */
class Email extends EmailSummary {


    /**
     * The main text body for this email.
     *
     * @var string
     * @validation required
     * @ormType LONGTEXT
     */
    private $textBody;

    /**
     * Array of attachment summary objects summarising any attachments for this email
     *
     * @var \Kinicart\Objects\Communication\Attachment\AttachmentSummary[]
     *
     * @relationship
     * @isMultiple
     * @relatedClassName Kinicart\Objects\Communication\Attachment\AttachmentSummary
     * @relatedFields id=>parentObjectId,"Email"=>parentObjectType
     *
     */
    private $attachments;


    /**
     * An array of local filenames to use as attachments to this email.
     *
     * @unmapped
     * @var string[]
     */
    private $localAttachmentFiles = array();


    /**
     * Email constructor.
     *
     * @param string $sender
     * @param string[] $recipient
     * @param string $subject
     * @param string $textBody
     * @param string[] $cc
     * @param string[] $bcc
     * @param string $replyTo
     * @param integer $accountId
     */
    public function __construct($sender = null, $recipients = null, $subject = null, $textBody = null,
                                $cc = null, $bcc = null, $replyTo = null, $accountId = null) {

        $this->sender = $sender;
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->textBody = $textBody;
        $this->cc = $cc;
        $this->bcc = $bcc;
        $this->replyTo = $replyTo;
        $this->accountId = $accountId;
        $this->sentDate = date("d/m/Y H:i:s");

    }


    /**
     * @param string $date
     */
    public function setSentDate($sentDate) {
        $this->sentDate = $sentDate;
    }

    /**
     * @param string[] $cc
     */
    public function setCc($cc) {
        $this->cc = $cc;
    }

    /**
     * @param string[] $bcc
     */
    public function setBcc($bcc) {
        $this->bcc = $bcc;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * @param string $replyTo
     */
    public function setReplyTo($replyTo) {
        $this->replyTo = $replyTo;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @param string $from
     */
    public function setSender($sender) {
        $this->sender = $sender;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param string[] $recipients
     */
    public function setRecipients($recipients) {
        $this->recipients = $recipients;
    }

    /**
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getTextBody() {
        return $this->textBody;
    }

    /**
     * @param string $textBody
     */
    public function setTextBody($textBody) {
        $this->textBody = $textBody;
    }

    /**
     * @return \Kinicart\Objects\Communication\Attachment\AttachmentSummary[]
     */
    public function getAttachments() {
        return $this->attachments;
    }

    /**
     * @param \Kinicart\Objects\Communication\Attachment\AttachmentSummary[] $attachments
     */
    public function setAttachments($attachments) {
        $this->attachments = $attachments;
    }

    /**
     * @return string[]
     */
    public function getLocalAttachmentFiles() {
        return $this->localAttachmentFiles;
    }

    /**
     * @param string[] $localAttachmentFiles
     */
    public function setLocalAttachmentFiles($localAttachmentFiles) {
        $this->localAttachmentFiles = $localAttachmentFiles;
    }


    /**
     * Overridden save method to also save any attachments which may have been added as local files.
     */
    public function save() {
        parent::save();

        // Save any local attachments if set.
        if ($this->localAttachmentFiles) {
            foreach ($this->getLocalAttachmentFiles() as $file) {

                $attachment = new Attachment("Email", $this->id, $file, null, $this->accountId);
                $attachment->save();
            }

            $this->localAttachmentFiles = null;
        }
    }


}
