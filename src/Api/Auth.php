<?php
namespace WeTransfer\Api;

use InvalidArgumentException;

use WeTransfer\Http\ApiRequest;

/**
 * Authorize the API key
 *
 * @package WeTransfer\Api
 */
class Auth
{
  // @var WeTransfer\Http\ApiRequest Request service
    private $api;

    public function __construct(ApiRequest $api)
    {
        $this->api = $api;
    }

  /**
   * Authorize the existing API key
   */
    public function authorize()
    {
        return $this->api->request('POST', '/authorize');
    }
}
