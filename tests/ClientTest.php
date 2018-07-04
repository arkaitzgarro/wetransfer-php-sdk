<?php
namespace WeTransfer;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
  public function setUp()
  {
    Client::setApiKey('secret-api-key');
  }

  public function testApiKeyValue() {
    $this->assertEquals(Client::getApiKey(), 'secret-api-key');
  }

  public function testEmptyApiKeyException() {
    $this->expectException(InvalidArgumentException::class);
    Client::setApiKey('');
  }
}
