<?php
namespace WeTransfer\Test\Api;

use PHPUnit\Framework\TestCase;

use WeTransfer\Api\Files;
use WeTransfer\Entity\Transfer;
use WeTransfer\Http\ApiRequest;

class FilesTest extends TestCase
{
    private $api;
    private $mockHandler;

    public function setup()
    {
        $this->mockHandler = new \GuzzleHttp\Handler\MockHandler();
        $httpClient = new \GuzzleHttp\Client([
        'handler' => $this->mockHandler,
        ]);

        $http = new ApiRequest();
        $http->setHttpClient($httpClient);

        $this->api = new Files($http);
    }

    public function testAddFiles()
    {
        $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], json_encode([
        [
        'id' => 'random-id',
        'local_identifier' => 'local-identifier',
        'name' => 'file-name.txt',
        'size' => 1024,
        'meta' => [
          'multipart_parts' => 3,
          'multipart_upload_id' => 'multipart-upload-id'
        ]
        ]
        ])));

        $transfer = new Transfer([
        'id' => 'random-id',
        'name' => 'My Transfer',
        'description' => '',
        'shortened_url' => '',
        ]);

        $response = $this->api->addFiles($transfer, [
        [
        'filename' => 'file-name.txt',
        'filesize' => 1024
        ]
        ]);

        $file = $transfer->getFiles()[0];
        $this->assertEquals('random-id', $file->getId());
        $this->assertEquals('file-name.txt', $file->getName());
        $this->assertEquals(1024, $file->getSize());
        $this->assertEquals('file', $file->getContentIdentifier());
        $this->assertEquals('local-identifier', $file->getLocalIdentifier());
        $this->assertEquals(3, $file->getNumberOfParts());
        $this->assertEquals('multipart-upload-id', $file->getMultipartId());
    }
}
