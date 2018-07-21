<?php
namespace WeTransfer\Api;

use InvalidArgumentException;

use Ramsey\Uuid\Uuid;

use WeTransfer\Entity\File as FileEntity;
use WeTransfer\Entity\Transfer as TransferEntity;
use WeTransfer\Http\ApiRequest;

/**
 * Files operations
 *
 * @package WeTransfer\Api
 */
class Files
{
  // @var WeTransfer\Http\ApiRequest Request service
    private $api;

    public function __construct(ApiRequest $api)
    {
        $this->api = $api;
    }

  /**
   * Add files to an existing transfer.
   *
   * @param TransferEntity  $transfer Existing transfer object
   * @param array           $files    A collection of files to be added to the transfer
   *
   * @return TransferEntity $transfer
   */
    public function addFiles(TransferEntity $transfer, array $files = [])
    {
        $transferId = $transfer->getId();
        $response = $this->api->request('POST', "/transfers/{$transferId}/items", ['items' => $this->normalizeFutureFiles($files)]);

        $transfer->addFiles($this->normalizeRemoteFiles($response));

        return $transfer;
    }

  /**
   * Create a S3 upload URL for a given file and part number
   *
   * @param FileEntity $file       Existing file object
   * @param int        $partNumber Which part number we want to upload
   *
   * @return string                Upload URL
   */
    public function createUploadUrl(FileEntity $file, $partNumber)
    {
        $fileId = $file->getId();
        $multipartId = $file->getMultipartId();

        $response = $this->api->request('GET', "/files/{$fileId}/uploads/{$partNumber}/{$multipartId}");

        return $response['upload_url'];
    }

  /**
   * Uploads a chunk of the file to S3
   *
   * @param string $uploadUrl S3 upload URL
   * @param string $content   Content to upload
   */
    public function uploadPart($uploadUrl, $content)
    {
        return $this->api->upload($uploadUrl, $content);
    }

  /**
   * Marks the file upload as completed
   *
   * @param FileEntity $file Existing file object
   */
    public function completeUpload($file)
    {
        $fileId = $file->getId();

        return $this->api->request('POST', "/files/{$fileId}/uploads/complete");
    }

    private function normalizeFutureFiles(array $files = [])
    {
        return array_map([$this, 'mapFutureFile'], $files);
    }

    private function mapFutureFile($file)
    {
        return [
        'filename' => $file['filename'],
        'filesize' => intval($file['filesize']),
        'content_identifier' => 'file',
        'local_identifier' => Uuid::uuid4()->toString()
        ];
    }

    private function normalizeRemoteFiles(array $files = [])
    {
        return array_map([$this, 'mapRemoteFile'], $files);
    }

    private function mapRemoteFile($file)
    {
        return new FileEntity($file, $this);
    }
}
