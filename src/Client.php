<?php
namespace WeTransfer;

use InvalidArgumentException;

use WeTransfer\Http\ApiRequest;
use WeTransfer\Auth;

/**
 * The WeTransfer API client
 *
 * @package WeTransfer
 */
final class Client
{
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

    ApiRequest::setApiKey($apiKey);
    Auth::authorize();
  }
}
