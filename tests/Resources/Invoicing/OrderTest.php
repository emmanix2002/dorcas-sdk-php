<?php

namespace Hostville\Dorcas\Tests\Resources\Invoicing;


use Hostville\Dorcas\Resources\Invoicing\Order;
use Hostville\Dorcas\Sdk;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    /** @var Order */
    protected $resource;

    public function setUp()
    {
        $sdk = new Sdk(['credentials' => ['id' => 1, 'secret' => 'fake-secret-code']]);
        $this->resource = $sdk->createOrderResource();
    }

    public function testGetName()
    {
        $this->assertEquals('Order', $this->resource->getName());
    }
    
}