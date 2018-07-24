<?php
namespace WeTransfer\Api;

use WeTransfer\Entity\Transfer as TransferEntity;
use WeTransfer\Http\Api;

/**
 * Transfer operations
 *
 * @package WeTransfer\Api
 */
class Transfer
{
    /**
     * Creates a new transfer given a name and a description
     *
     * @param string $name        Name of the transfer
     * @param string $description Optional descritpion for the transfer
     *
     * @return WeTransfer\Entity\Transfer $transfer
     */
    public static function create($name, $description = '')
    {
        $response = Api::request('POST', '/transfers', [
            'name' => $name,
            'description' => $description
        ]);

        return new TransferEntity($response);
    }
}
