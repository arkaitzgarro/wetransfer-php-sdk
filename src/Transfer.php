<?php
namespace WeTransfer;

use WeTransfer\Api\Transfer as TransferApi;
use WeTransfer\Api\Links as LinksApi;
use WeTransfer\Api\Files as FilesApi;
use WeTransfer\Entity\Transfer as TransferEntity;

/**
 * The WeTransfer API client for transfer
 *
 * @package WeTransfer
 */
class Transfer
{
    /**
     * Creates a new transfer given a name and a description
     *
     * @param string $name        Name of the transfer
     * @param string $description Optional descritpion for the transfer
     *
     * @return Transfer $transfer
     */
    public static function create($name, $description = '')
    {
        // TODO: check for arguments validity
        return TransferApi::create($name, $description);
    }

    /**
     * Adds links to an existing transfer
     *
     * @param Transfer $transfer An existing transfer instance
     * @param array    $links    A list of links to be added to the transfer
     *
     * @return Transfer $transfer
     */
    public static function addLinks(TransferEntity $transfer, array $links = [])
    {
        // TODO: check for arguments validity
        return LinksApi::addLinks($transfer, $links);
    }

    /**
     * Adds files to an existing transfer
     *
     * @param Transfer $transfer An existing transfer instance
     * @param array    $files    A list of files to be added to the transfer
     *
     * @return Transfer $transfer
     */
    public static function addFiles(TransferEntity $transfer, array $files = [])
    {
        return FilesApi::addFiles($transfer, $files);
    }
}
