<?php
namespace WeTransfer\Entity;

use WeTransfer\Entity\Abstracts\Item;

class File extends Item
{
  const MAX_CHUNK_SIZE = 6 * 1024 * 1024;

  // @var object File name.
  private $name;

  // @var string File size.
  private $size;

  // @var object File multipart meta data.
  private $meta;

  // @var WeTransfer\Api\Files File api service
  private $fileApi;

  public function __construct($file, $fileApi)
  {
    parent::__construct($file);

    $this->name = $file['name'];
    $this->size = $file['size'];
    $this->meta = $file['meta'];
    $this->contentIdentifier = 'file';

    $this->fileApi = $fileApi;
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

  /**
   * Given a file resource, and the number of parts that must be uploaded to S3,
   * it chunkes the file and uploads each part sequentially. Completes the file upload at the end.
   *
   * @param pointer $resource A pointer to a file created with fopen
   */
  public function upload($resource)
  {
    for ($partNumber = 1; $partNumber <= $this->getNumberOfParts(); $partNumber++) {
      $uploadUrl = $this->fileApi->createUploadUrl($this, $partNumber);
      $this->uploadPart($uploadUrl, $resource);
    }

    $this->fileApi->completeUpload($this);
  }

  /**
   * Uploads a chunk of the file to S3
   *
   * @param string  $uploadUrl S3 upload URL
   * @param pointer $resource  A pointer to a file created with fopen
   */
  private function uploadPart($uploadUrl, $resource)
  {
    $content = fread($resource, self::MAX_CHUNK_SIZE);
    $this->fileApi->uploadPart($uploadUrl, $content);
  }
}
