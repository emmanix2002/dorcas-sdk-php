<?php

namespace Hostville\Dorcas\Tests\Resources\Crm;


use Hostville\Dorcas\Resources\Crm\Customer;
use Hostville\Dorcas\Sdk;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    /** @var Customer */
    protected $resource;

    public function setUp()
    {
        $sdk = new Sdk(['credentials' => ['id' => 1, 'secret' => 'fake-secret-code']]);
        $this->resource = $sdk->createCustomerResource();
    }

    public function testGetName()
    {
        $this->assertEquals('Customer', $this->resource->getName());
    }

    public function testInvalidRelationships()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->resource->relationships("nonexistent");
    }

    public function testValidRelationships()
    {
        $r = $this->resource->relationships("company", "contacts", "orders");
        $this->assertEquals($r, $this->resource);
    }
}