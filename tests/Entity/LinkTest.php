<?php
namespace WeTransfer\Test\Entity;

use PHPUnit\Framework\TestCase;

use WeTransfer\Entity\Link;

class LinkTest extends TestCase
{
    private $link;

    public function setup()
    {
        $this->link = new Link([
            'id' => 'random-id',
            'content_identifier' => 'web_content',
            'local_identifier' => 'local-identifier',
            'url' => 'https://wetransfer.com',
            'meta' => [
                'title' => 'WeTransfer'
            ]
        ]);
    }

    public function testConstructor()
    {
        $this->assertEquals('random-id', $this->link->getId());
        $this->assertEquals('web_content', $this->link->getContentIdentifier());
        $this->assertEquals('local-identifier', $this->link->getLocalIdentifier());
        $this->assertEquals('WeTransfer', $this->link->getTitle());
        $this->assertEquals('https://wetransfer.com', $this->link->getUrl());
        $this->assertEquals(['title' => 'WeTransfer'], $this->link->getMetaData());
    }

    public function testJsonOutput()
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"random-id","url":"https://wetransfer.com","meta":{"title":"WeTransfer"}}',
            json_encode($this->link)
        );
    }
}
