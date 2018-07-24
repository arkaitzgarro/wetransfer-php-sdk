<?php
namespace WeTransfer\Test\Api;

use PHPUnit\Framework\TestCase;

use WeTransfer\Api\Transfer;
use WeTransfer\Http\Api;

class TransferTest extends TestCase
{
    private $mockHandler;

    public function setup()
    {
        $this->mockHandler = new \GuzzleHttp\Handler\MockHandler();
        $httpClient = new \GuzzleHttp\Client([
            'handler' => $this->mockHandler,
        ]);

        Api::setHttpClient($httpClient);
    }

    public function testCreate()
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
        $this->assertEquals([], $transfer->getLinks());
        $this->assertEquals([], $transfer->getFiles());
    }
}
