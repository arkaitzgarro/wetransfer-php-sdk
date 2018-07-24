<?php
namespace WeTransfer\Entity\Abstracts;

abstract class Item
{
    // @var string Item id.
    protected $id;

    // @var object Item content identifier: file or web_content for now.
    protected $contentIdentifier;

    // @var string Item local identifier.
    protected $localIdentifier;

    public function __construct($item)
    {
        $this->id = $item['id'];
        $this->localIdentifier = $item['local_identifier'];
    }

    /**
     * Get item id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get item content identifier.
     */
    public function getContentIdentifier()
    {
        return $this->contentIdentifier;
    }

    /**
     * Get item local identifier.
     */
    public function getLocalIdentifier()
    {
        return $this->localIdentifier;
    }
}
