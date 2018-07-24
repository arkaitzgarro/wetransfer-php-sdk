<?php
namespace WeTransfer\Test\Http;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use WeTransfer\Http\Api;

class ApiRequestTest extends TestCase
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

    public function testJwtWithEmptyArgumentRaisesAnException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        Api::setJWT('');
    }

    public function testRequestWithoutParams()
    {
        $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], '{"key": "value"}'));

        $response = Api::request('GET', '/endpoint');
        $this->assertEquals(['key' => 'value'], $response);
        $this->assertEquals('jwt', Api::getJWT());
    }

    public function testUpload()
    {
        $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], '[]'));

        $response = Api::upload('/endpoint', 'some-bytes');
        $this->assertEquals([], $response);
    }
}
