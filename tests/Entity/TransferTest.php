<?php
namespace WeTransfer\Test\Entity;

use PHPUnit\Framework\TestCase;

use WeTransfer\Entity\File;
use WeTransfer\Entity\Link;
use WeTransfer\Entity\Transfer;

class TransferTest extends TestCase
{
    private $file;
    private $link;
    private $transfer;

    public function setUp()
    {
        $this->link = new Link([
            'id' => 'random-id',
            'content_identifier' => 'web_content',
            'local_identifier' => 'local-identifier',
            'url' => 'https://wetransfer.com',
            'meta' => []
        ]);

        $this->file = new File([
            'id' => 'random-id',
            'content_identifier' => 'file',
            'local_identifier' => 'local-identifier',
            'name' => 'file-name.txt',
            'size' => 1024,
            'meta' => []
        ], null);

        $this->transfer = new Transfer([
            'id' => 'random-id',
            'name' => 'My Transfer',
            'description' => '',
            'shortened_url' => 'https://we.tl/random-hash',
        ]);
    }

    public function testConstructor()
    {
        $this->assertEquals('random-id', $this->transfer->getId());
        $this->assertEquals('My Transfer', $this->transfer->getName());
        $this->assertEquals('', $this->transfer->getDescription());
        $this->assertEquals('https://we.tl/random-hash', $this->transfer->getShortenedUrl());
        $this->assertEquals([], $this->transfer->getFiles());
        $this->assertEquals([], $this->transfer->getLinks());
    }

    public function testAddLinks()
    {
        $this->transfer->addLinks([$this->link]);
        $this->assertEquals(1, count($this->transfer->getLinks()));
        $this->assertEquals(0, count($this->transfer->getFiles()));
    }

    public function testAddFiles()
    {
        $this->transfer->addFiles([$this->file]);
        $this->assertEquals(1, count($this->transfer->getFiles()));
        $this->assertEquals(0, count($this->transfer->getLinks()));
    }
}
