<?php

namespace Hostville\Dorcas\Tests\Resources\Crm;


use Hostville\Dorcas\Resources\Crm\ContactField;
use Hostville\Dorcas\Sdk;
use PHPUnit\Framework\TestCase;

class ContactFieldTest extends TestCase
{
    /** @var ContactField */
    protected $resource;

    public function setUp()
    {
        $sdk = new Sdk(['credentials' => ['id' => 1, 'secret' => 'fake-secret-code']]);
        $this->resource = $sdk->createContactFieldResource();
    }

    public function testGetName()
    {
        $this->assertEquals('ContactField', $this->resource->getName());
    }

    public function testInvalidRelationships()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->resource->relationships("nonexistent");
    }

    public function testValidRelationships()
    {
        $r = $this->resource->relationships("customers");
        $this->assertEquals($r, $this->resource);
    }
}