<?php
namespace WeTransfer\Test\Entity;

use PHPUnit\Framework\TestCase;

use WeTransfer\Entity\Link;

class LinkTest extends TestCase
{
    public function testConstructor()
    {
        $link = new Link([
            'id' => 'random-id',
            'content_identifier' => 'web_content',
            'local_identifier' => 'local-identifier',
            'url' => 'https://wetransfer.com',
            'meta' => [
                'title' => 'WeTransfer'
            ]
        ]);

        $this->assertEquals('random-id', $link->getId());
        $this->assertEquals('web_content', $link->getContentIdentifier());
        $this->assertEquals('local-identifier', $link->getLocalIdentifier());
        $this->assertEquals('WeTransfer', $link->getTitle());
        $this->assertEquals('https://wetransfer.com', $link->getUrl());
        $this->assertEquals(['title' => 'WeTransfer'], $link->getMetaData());
    }
}
