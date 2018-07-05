<?php
namespace WeTransfer;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;
use WeTransfer\Http\ApiRequest;

class ClientTest extends TestCase
{
  public function setUp()
  {
    Client::setApiKey('secret-api-key');
  }

  public function testApiKeyValue() {
    $this->assertEquals(ApiRequest::getApiKey(), 'secret-api-key');
  }

  public function testEmptyApiKeyException() {
    $this->expectException(InvalidArgumentException::class);
    Client::setApiKey('');
  }
}
