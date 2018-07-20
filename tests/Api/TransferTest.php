<?php
namespace WeTransfer\Test\Api;

use PHPUnit\Framework\TestCase;

use WeTransfer\Api\Transfer as TransferApi;
use WeTransfer\Entity\Transfer as TransferEntity;
use WeTransfer\Http\ApiRequest;

class TransferTest extends TestCase
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

    $this->api = new TransferApi($http);
  }

  public function testCreate()
  {
    $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], json_encode([
      'id' => 'random-id',
      'name' => 'My Transfer',
      'description' => 'Description',
      'shortened_url' => 'https://wt.com/random'
    ])));

    $transfer = $this->api->create('My Transfer', 'Description');

    $this->assertEquals('random-id', $transfer->getId());
    $this->assertEquals('My Transfer', $transfer->getName());
    $this->assertEquals('Description', $transfer->getDescription());
    $this->assertEquals('https://wt.com/random', $transfer->getShortenedUrl());
    $this->assertEquals([], $transfer->getLinks());
    $this->assertEquals([], $transfer->getFiles());
  }
}
