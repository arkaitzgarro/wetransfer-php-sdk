<?php
namespace WeTransfer\Entity;

use WeTransfer\Entity\Link as LinkEntity;

class Transfer
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
  public function getId() {
    return $this->id;
  }

  /**
   * Get Transfer name.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Get Transfer description.
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Get Transfer shortened URL.
   */
  public function getShortenedUrl() {
    return $this->shortenedUrl;
  }

  /**
   * Add links
   */
  public function addLinks(array $links = [])
  {
    array_push($this->items, $links);
  }
}
