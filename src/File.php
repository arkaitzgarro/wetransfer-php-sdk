<?php
namespace WeTransfer;

use WeTransfer\Api\Files as FilesApi;
use WeTransfer\Entity\File as FileEntity;

/**
 * The WeTransfer API client for files
 *
 * @package WeTransfer
 */
class File
{
    const MAX_CHUNK_SIZE = 6 * 1024 * 1024;

    /**
     * Given a file resource, and the number of parts that must be uploaded to S3,
     * it chunkes the file and uploads each part sequentially. Completes the file upload at the end.
     *
     * @param File    $file     A file entity object
     * @param pointer $resource A pointer to a file created with fopen
     *
     * @return GuzzleHttp\Psr7\Response
     */
    public static function upload(FileEntity $file, $resource)
    {
        for ($partNumber = 1; $partNumber <= $file->getNumberOfParts(); $partNumber++) {
            $uploadUrl = FilesApi::createUploadUrl($file->getId(), $file->getMultipartId(), $partNumber);
            self::uploadPart($uploadUrl['upload_url'], $resource);
        }

        return FilesApi::completeUpload($file);
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
        return FilesApi::createUploadUrl($fileId, $multipartId, $partNumber);
    }

    /**
     * Uploads a chunk of the file to S3
     *
     * @param string  $uploadUrl S3 upload URL
     * @param pointer $resource  A pointer to a file created with fopen
     */
    private static function uploadPart($uploadUrl, $resource)
    {
        $content = fread($resource, self::MAX_CHUNK_SIZE);
        FilesApi::uploadPart($uploadUrl, $content);
    }
}
