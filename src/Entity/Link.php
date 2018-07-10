<?php
namespace WeTransfer\Entity;

class Link
{
  // @var string Link id.
  private $id;

  // @var object Link meta data.
  private $meta;

  // @var string Link target URL.
  private $url;

  public function __construct($link)
  {
    $this->id = $link['id'];
    $this->meta = $link['meta'];
    $this->url = $link['url'];
  }

  /**
   * Get Link id.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Get Link meta.
   */
  public function getMetadata() {
    return $this->meta;
  }

  /**
   * Get Link url.
   */
  public function getUrl() {
    return $this->url;
  }
}
