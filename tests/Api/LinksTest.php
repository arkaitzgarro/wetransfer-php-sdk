<?php
namespace WeTransfer\Test\Api;

use PHPUnit\Framework\TestCase;

use WeTransfer\Api\Links;
use WeTransfer\Entity\Transfer;
use WeTransfer\Http\Api;

class LinksTest extends TestCase
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

        $transfer = new Transfer([
            'id' => 'random-id',
            'name' => 'My Transfer',
            'description' => '',
            'shortened_url' => '',
        ]);

        $response = Links::addLinks($transfer, [
            [
                'url' => 'https://en.wikipedia.org/wiki/Japan',
                'meta' => [
                    'title' => 'Japan'
                ]
            ]
        ]);

        $link = $transfer->getLinks()[0];
        $this->assertEquals('random-id', $link->getId());
        $this->assertEquals('web_content', $link->getContentIdentifier());
        $this->assertEquals('local-identifier', $link->getLocalIdentifier());
        $this->assertEquals('Japan', $link->getTitle());
        $this->assertEquals('https://en.wikipedia.org/wiki/Japan', $link->getUrl());
    }
}
