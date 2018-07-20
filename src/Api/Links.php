<?php
namespace WeTransfer\Api;

use InvalidArgumentException;

use Ramsey\Uuid\Uuid;

use WeTransfer\Entity\Link as LinkEntity;
use WeTransfer\Entity\Transfer as TransferEntity;
use WeTransfer\Http\ApiRequest;

/**
 * Links operations
 *
 * @package WeTransfer\Api
 */
class Links
{
  // @var WeTransfer\Http\ApiRequest Request service
  private $api;

  public function __construct(ApiRequest $api)
  {
    $this->api = $api;
  }

  /**
   * Add links to an existing transfer.
   *
   * @param TransferEntity $transfer Existing transfer object
   * @param array          $links    A collection of links to be added to the transfer
   *
   * @return TransferEntity $transfer
   */
  public function addLinks(TransferEntity $transfer, array $links = [])
  {
    $transferId = $transfer->getId();
    $response = $this->api->request('POST', "/transfers/{$transferId}/items", ['items' => $this->normalizeFutureLinks($links)]);

    $transfer->addLinks($this->normalizeRemoteLinks($response));

    return $transfer;
  }

  private function normalizeFutureLinks(array $links = [])
  {
    return array_map([$this, 'mapFutureLink'], $links);
  }

  private function mapFutureLink($link)
  {
    return [
      'url' => $link['url'],
      'content_identifier' => 'web_content',
      'local_identifier' => Uuid::uuid4()->toString(),
      'meta' => [
        'title' => $link['meta']['title']
      ]
    ];
  }

  private function normalizeRemoteLinks(array $links = [])
  {
    return array_map([$this, 'mapRemoteLink'], $links);
  }

  private function mapRemoteLink($link)
  {
    return new LinkEntity($link);
  }
}
