<?php
namespace WeTransfer;

use InvalidArgumentException;

use WeTransfer\Http\ApiRequest;

/**
 * Create a transfer
 *
 * @package WeTransfer\Transfer
 */
final class Transfer
{
  /**
   * Create a transfer
   */
  public static function create($name, $description = '')
  {
    return ApiRequest::request('POST', '/transfers', [
      'name' => $name,
      'description' => $description
    ]);
  }
}
