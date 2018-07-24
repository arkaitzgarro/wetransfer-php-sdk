<?php
namespace WeTransfer\Test\Api;

use PHPUnit\Framework\TestCase;

use WeTransfer\Api\Auth;
use WeTransfer\Http\Api;

class AuthTest extends TestCase
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

    public function testAuthorize()
    {
        $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], '{"key": "value"}'));

        $response = Auth::authorize();
        $this->assertEquals(['key' => 'value'], $response);
    }
}
