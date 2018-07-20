<?php
namespace WeTransfer\Test\Http;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use WeTransfer\Http\ApiRequest;

class ApiRequestTest extends TestCase
{
  private $mockHandler;
  private $api;

  public function setUp()
  {
    $this->mockHandler = new \GuzzleHttp\Handler\MockHandler();
    $httpClient = new \GuzzleHttp\Client([
      'handler' => $this->mockHandler,
    ]);

    $this->api = new ApiRequest();
    $this->api->setHttpClient($httpClient);
  }

  public function testJwtWithEmptyArgumentRaisesAnException()
  {
    $this->setExpectedException('\InvalidArgumentException');
    $this->api->setJWT('');
  }

  public function testRequestWithoutParams()
  {
    $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], '{"key": "value"}'));

    $response = $this->api->request('GET', '/endpoint');
    $this->assertEquals(['key' => 'value'], $response);
  }

  public function testUpload()
  {
    $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], '[]'));

    $response = $this->api->upload('/endpoint', 'some-bytes');
    $this->assertEquals([], $response);
  }
}
