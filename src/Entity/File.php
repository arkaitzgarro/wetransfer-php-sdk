<?php
namespace WeTransfer\Entity;

use WeTransfer\Entity\Abstracts\Item;

class File extends Item
{
    // @var object File name.
    private $name;

    // @var string File size.
    private $size;

    // @var object File multipart meta data.
    private $meta;

    public function __construct($file)
    {
        parent::__construct($file);

        $this->name = $file['name'];
        $this->size = $file['size'];
        $this->meta = $file['meta'];
        $this->contentIdentifier = 'file';
    }

    /**
     * Get file name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get file size.
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get file number of multipart parts.
     */
    public function getNumberOfParts()
    {
        return $this->meta['multipart_parts'];
    }

    /**
     * Get file multipart id.
     */
    public function getMultipartId()
    {
        return $this->meta['multipart_upload_id'];
    }
}
