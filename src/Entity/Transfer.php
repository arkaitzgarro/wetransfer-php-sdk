<?php
namespace WeTransfer\Entity;

use JsonSerializable;

use WeTransfer\Entity\Abstracts\Item;

class Transfer implements JsonSerializable
{
    // @var string Transfer id.
    private $id;

    // @var string Transfer name.
    private $name;

    // @var string Transfer description.
    private $description;

    // @var string Transfer shortened URL.
    private $shortenedUrl;

    // @var array Transfer items: files and links.
    private $items;

    public function __construct($transfer)
    {
        $this->id = $transfer['id'];
        $this->name = $transfer['name'];
        $this->description = $transfer['description'];
        $this->shortenedUrl = $transfer['shortened_url'];

        $this->items = [];
    }

    /**
     * Get Transfer id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Transfer name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Transfer description.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get Transfer shortened URL.
     */
    public function getShortenedUrl()
    {
        return $this->shortenedUrl;
    }

    /**
     * Add links
     */
    public function addLinks(array $links = [])
    {
        $this->items = array_merge($this->items, $links);
    }

    /**
     * Add files
     */
    public function addFiles(array $files = [])
    {
        $this->items = array_merge($this->items, $files);
    }

    /**
     * Get links from items array
     */
    public function getLinks()
    {
        return array_filter($this->items, [$this, 'filterArrayLink']);
    }

    /**
     * Get files from items array
     */
    public function getFiles()
    {
        return array_filter($this->items, [$this, 'filterArrayFile']);
    }

    private function filterArrayLink(Item $link)
    {
        return $link->getContentIdentifier() == 'web_content';
    }

    private function filterArrayFile(Item $file)
    {
        return $file->getContentIdentifier() == 'file';
    }

    public function jsonSerialize()
    {
        return [
            'id'   => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'shortened_url' => $this->getShortenedUrl(),
            'links' => json_encode($this->getLinks()),
            'files' => json_encode($this->getFiles())
        ];
    }
}
