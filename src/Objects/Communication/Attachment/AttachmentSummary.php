<?php


namespace Kinicart\Objects\Communication\Attachment;


use Kinikit\Persistence\UPF\Object\ActiveRecord;

/**
 * Attachment summary for use when summarising attachments.
 *
 * Class AttachmentSummary
 */
class AttachmentSummary extends ActiveRecord {


    /**
     * Unique id for this attachment
     *
     * @var integer
     */
    protected $id;


    /**
     * The type of object which this links back to e.g. Email.
     *
     * @var string
     */
    protected $parentObjectType;

    /**
     * The id of the object which this links back to.
     *
     * @var integer
     */
    protected $parentObjectId;


    /**
     * Filename for the attachment
     *
     * @var string
     */
    protected $attachmentFilename;

    /**
     * Mime type for the attachment
     *
     * @var string
     */
    protected $mimeType = "text/plain";


    /**
     * @return mixed
     */
    public function getAttachmentFilename() {
        return $this->attachmentFilename;
    }

    /**
     * @return string
     */
    public function getMimeType() {
        return $this->mimeType;
    }


}
