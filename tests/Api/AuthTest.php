<?php
namespace WeTransfer\Test\Api;

use PHPUnit\Framework\TestCase;

use WeTransfer\Api\Auth;
use WeTransfer\Http\ApiRequest;

class AuthTest extends TestCase
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

        $this->api = new Auth($http);
    }

    public function testAuthorize()
    {
        $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], '{"key": "value"}'));

        $response = $this->api->authorize();
        $this->assertEquals(['key' => 'value'], $response);
    }
}
