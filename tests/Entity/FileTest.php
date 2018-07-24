<?php
namespace WeTransfer\Test\Entity;

use PHPUnit\Framework\TestCase;

use WeTransfer\Entity\File;

class FileTest extends TestCase
{
    private $file;

    public function setup()
    {
        $this->file = new File([
            'id' => 'random-id',
            'content_identifier' => 'file',
            'local_identifier' => 'local-identifier',
            'name' => 'file-name.txt',
            'size' => '1024',
            'meta' => [
                'multipart_parts' => 2,
                'multipart_upload_id' => 'multipart-upload-id'
            ]
        ], null);
    }

    public function testConstructor()
    {
        $this->assertEquals('random-id', $this->file->getId());
        $this->assertEquals('file', $this->file->getContentIdentifier());
        $this->assertEquals('file-name.txt', $this->file->getName());
        $this->assertEquals(1024, $this->file->getSize());
        $this->assertEquals(2, $this->file->getNumberOfParts());
        $this->assertEquals('multipart-upload-id', $this->file->getMultipartId());
    }
}
