<?php
namespace WeTransfer;

use InvalidArgumentException;

use WeTransfer\Http\ApiRequest;

/**
 * Authorize the API key
 *
 * @package WeTransfer\Auth
 */
final class Auth
{
  /**
   * Authorize the existing API key
   */
  public static function authorize()
  {
    if (ApiRequest::getApiKey() === null) {
      throw new InvalidArgumentException('API key value cannot be empty. Please call WeTransfer\Client::setApiKey("YOUR API KEY") first.');
    }

    $auth = ApiRequest::request('POST', '/authorize');

    ApiRequest::setJWT($auth['token']);
  }
}
