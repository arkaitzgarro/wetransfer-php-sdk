<?php
namespace WeTransfer\Api;

use Ramsey\Uuid\Uuid;

use WeTransfer\Entity\Link as LinkEntity;
use WeTransfer\Entity\Transfer as TransferEntity;
use WeTransfer\Http\Api;

/**
 * Links operations
 *
 * @package WeTransfer\Api
 */
class Links
{
    /**
     * Add links to an existing transfer.
     *
     * @param WeTransfer\Entity\Transfer $transfer Existing transfer object
     * @param array                      $links    A collection of links to be added to the transfer
     *
     * @return WeTransfer\Entity\Transfer $transfer
     */
    public static function addLinks(TransferEntity $transfer, array $links = [])
    {
        $transferId = $transfer->getId();
        $response = Api::request('POST', "/transfers/{$transferId}/items", ['items' => self::normalizeFutureLinks($links)]);

        $transfer->addLinks(self::normalizeRemoteLinks($response));

        return $transfer;
    }

    private static function normalizeFutureLinks(array $links = [])
    {
        return array_map('self::mapFutureLink', $links);
    }

    private static function mapFutureLink($link)
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

    private static function normalizeRemoteLinks(array $links = [])
    {
        return array_map('self::mapRemoteLink', $links);
    }

    private static function mapRemoteLink($link)
    {
        return new LinkEntity($link);
    }
}
