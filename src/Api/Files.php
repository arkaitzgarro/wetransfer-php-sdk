<?php
namespace WeTransfer\Api;

use Ramsey\Uuid\Uuid;

use WeTransfer\Entity\File as FileEntity;
use WeTransfer\Entity\Transfer as TransferEntity;
use WeTransfer\Http\Api;

/**
 * Files operations
 *
 * @package WeTransfer\Api
 */
class Files
{
    /**
     * Add files to an existing transfer.
     *
     * @param WeTransfer\Entity\Transfer  $transfer Existing transfer object
     * @param array                       $files    A collection of files to be added to the transfer
     *
     * @return WeTransfer\Entity\Transfer $transfer
     */
    public static function addFiles(TransferEntity $transfer, array $files = [])
    {
        $transferId = $transfer->getId();
        $response = Api::request('POST', "/transfers/{$transferId}/items", ['items' => self::normalizeFutureFiles($files)]);

        $transfer->addFiles(self::normalizeRemoteFiles($response));

        return $transfer;
    }

    /**
     * Create a S3 upload URL for a given file and part number
     *
     * @param string $fileId      Existing file identifier
     * @param string $multipartId Existing file miltipart identifier
     * @param int    $partNumber  Which part number we want to upload
     *
     * @return GuzzleHttp\Psr7\Response          Upload URL object
     */
    public static function createUploadUrl($fileId, $multipartId, $partNumber)
    {
        return Api::request('GET', "/files/{$fileId}/uploads/{$partNumber}/{$multipartId}");
    }

    /**
     * Uploads a chunk of the file to S3
     *
     * @param string $uploadUrl S3 upload URL
     * @param string $content   Content to upload
     *
     * @return GuzzleHttp\Psr7\Response
     */
    public static function uploadPart($uploadUrl, $content)
    {
        return Api::upload($uploadUrl, $content);
    }

    /**
     * Marks the file upload as completed
     *
     * @param WeTransfer\Entity\File $file Existing file object
     *
     * @return GuzzleHttp\Psr7\Response
     */
    public static function completeUpload(FileEntity $file)
    {
        $fileId = $file->getId();

        return Api::request('POST', "/files/{$fileId}/uploads/complete");
    }

    private static function normalizeFutureFiles(array $files = [])
    {
        return array_map('self::mapFutureFile', $files);
    }

    private static function mapFutureFile($file)
    {
        return [
            'filename' => $file['filename'],
            'filesize' => intval($file['filesize']),
            'content_identifier' => 'file',
            'local_identifier' => Uuid::uuid4()->toString()
        ];
    }

    private static function normalizeRemoteFiles(array $files = [])
    {
        return array_map('self::mapRemoteFile', $files);
    }

    private static function mapRemoteFile($file)
    {
        return new FileEntity($file);
    }
}
