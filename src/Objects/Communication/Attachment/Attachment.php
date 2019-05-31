<?php


namespace Kinicart\Objects\Communication\Attachment;


class Attachment extends AttachmentSummary {

    /**
     * The raw content of this attachment.
     *
     * @var string
     */
    private $content;

    
    public function __construct($parentObjectType = null, $parentObjectId = null, $attachmentFilename = null,
                                $mimeType = null, $content = null) {

        $this->parentObjectType = $parentObjectType;
        $this->parentObjectId = $parentObjectId;
        $this->attachmentFilename = $attachmentFilename;
        $this->mimeType = $mimeType;
        $this->content = $content;

    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param string $parentObjectType
     */
    public function setParentObjectType($parentObjectType) {
        $this->parentObjectType = $parentObjectType;
    }

    /**
     * @param int $parentObjectId
     */
    public function setParentObjectId($parentObjectId) {
        $this->parentObjectId = $parentObjectId;
    }

    /**
     * @param string $attachmentFilename
     */
    public function setAttachmentFilename($attachmentFilename) {
        $this->attachmentFilename = $attachmentFilename;
    }

    /**
     * @param string $mimeType
     */
    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content) {
        $this->content = $content;
    }


}
