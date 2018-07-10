<?php
namespace WeTransfer\Api;

use InvalidArgumentException;

use WeTransfer\Entity\Transfer as TransferEntity;
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
   * Creates a new transfer given a name and a description
   *
   * @param string $name        Name of the transfer
   * @param string $description Optional descritpion for the transfer
   *
   * @return TransferEntity $transfer
   */
  public function create($name, $description = '')
  {
    $response = $this->api->request('POST', '/transfers', [
      'name' => $name,
      'description' => $description
    ]);

    return new TransferEntity($response);
  }
}
