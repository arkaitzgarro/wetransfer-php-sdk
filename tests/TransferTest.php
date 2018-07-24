<?php
namespace WeTransfer\Test;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use WeTransfer\Transfer;
use WeTransfer\Entity\Transfer as TransferEntity;
use WeTransfer\Http\Api;

class TransferTest extends TestCase
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

    public function testCreateTransfer()
    {
        $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], json_encode([
            'id' => 'random-id',
            'name' => 'My Transfer',
            'description' => 'Description',
            'shortened_url' => 'https://wt.com/random'
        ])));

        $transfer = Transfer::create('My Transfer', 'Description');
        $this->assertEquals('random-id', $transfer->getId());
        $this->assertEquals('My Transfer', $transfer->getName());
        $this->assertEquals('Description', $transfer->getDescription());
        $this->assertEquals('https://wt.com/random', $transfer->getShortenedUrl());
    }

    public function testAddLinks()
    {
        $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], json_encode([
            [
                'id' => 'random-id',
                'local_identifier' => 'local-identifier',
                'url' => 'https://en.wikipedia.org/wiki/Japan',
                'meta' => [
                    'title' => 'Japan'
                ]
            ]
        ])));

        $transfer = new TransferEntity([
            'id' => 'random-id',
            'name' => 'My Transfer',
            'description' => 'Description',
            'shortened_url' => 'https://wt.com/random'
        ]);

        Transfer::addLinks($transfer, []);
        $this->assertEquals([], $transfer->getFiles());
        $this->assertEquals('random-id', $transfer->getLinks()[0]->getId());
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

        $transfer = new TransferEntity([
            'id' => 'random-id',
            'name' => 'My Transfer',
            'description' => 'Description',
            'shortened_url' => 'https://wt.com/random'
        ]);

        Transfer::addFiles($transfer, []);
        $this->assertEquals([], $transfer->getLinks());
        $this->assertEquals('random-id', $transfer->getFiles()[0]->getId());
    }
}
