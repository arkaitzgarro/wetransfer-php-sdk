<?php
namespace WeTransfer\Test;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use WeTransfer\File;
use WeTransfer\Entity\File as FileEntity;
use WeTransfer\Http\Api;

class FileTest extends TestCase
{
    private $mockHandler;

    public function setUp()
    {
        $this->mockHandler = new \GuzzleHttp\Handler\MockHandler();
        $httpClient = new \GuzzleHttp\Client([
            'handler' => $this->mockHandler,
        ]);

        Api::setHttpClient($httpClient);
    }

    public function testUploadFile()
    {
        $file = new FileEntity([
            'id' => 'random-id',
            'local_identifier' => 'local-identifier',
            'name' => 'file-name.txt',
            'size' => 1024,
            'meta' => [
                'multipart_parts' => 1,
                'multipart_upload_id' => 'multipart-upload-id'
            ]
        ]);

        // Get S3 upload url
        $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], json_encode([
            'upload_url' => 's3://a-very-long-url'
        ])));

        // Upload file request
        $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], '[]'));

        // Upload complete
        $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], '[]'));

        $response = File::upload($file, fopen(realpath('./README.md'), 'r'));
        $this->assertEquals([], $response);
    }
}
