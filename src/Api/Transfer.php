<?php
namespace WeTransfer\Api;

use InvalidArgumentException;

use WeTransfer\Http\ApiRequest;

/**
 * Transfer operations
 *
 * @package WeTransfer\Api
 */
class Transfer
{
  // @var WeTransfer\Http\ApiRequest Request service
  private $api;

  public function __construct(ApiRequest $api)
  {
    $this->api = $api;
  }

  /**
   * Create a transfer
   */
  public function create($name, $description = '')
  {
    return $this->api->request('POST', '/transfers', [
      'name' => $name,
      'description' => $description
    ]);
  }
}
