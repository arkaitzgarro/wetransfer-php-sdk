<?php
namespace WeTransfer\Test;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use WeTransfer\Client;

class ClientTest extends TestCase
{
    private $mockHandler;

    public function setUp()
    {
        $this->mockHandler = new \GuzzleHttp\Handler\MockHandler();
        $httpClient = new \GuzzleHttp\Client([
            'handler' => $this->mockHandler,
        ]);

        Client::setHttpClient($httpClient);
    }

    public function testInstantiationWithKeySucceeds()
    {
        $this->mockHandler->append(new \GuzzleHttp\Psr7\Response(200, [], '{"token": "jwt"}'));

        $jwt = Client::setApiKey('secret-api-key');
        $this->assertEquals('jwt', $jwt);
    }

    public function testInstantiationWithEmptyArgumentRaisesAnException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        Client::setApiKey('');
    }
}
