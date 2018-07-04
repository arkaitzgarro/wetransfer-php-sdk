<?php
namespace WeTransfer;

use InvalidArgumentException;

/**
 * The WeTransfer API client
 *
 * @package WeTransfer
 */
class Client
{
  // @var string The WeTransfer API key to be used for requests.
  private static $apiKey;

  /**
   * @return string The API key used for requests.
   */
  public static function getApiKey()
  {
    return self::$apiKey;
  }

  /**
   * Sets the API key to be used for requests.
   *
   * @param string $apiKey
   */
  public static function setApiKey($apiKey)
  {
    if(empty($apiKey)) {
      throw new InvalidArgumentException('API key value cannot be empty.');
    }

    self::$apiKey = $apiKey;
  }
}
