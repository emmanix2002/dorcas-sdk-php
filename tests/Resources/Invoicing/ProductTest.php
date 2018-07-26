<?php

namespace Hostville\Dorcas\Tests\Resources\Invoicing;


use Hostville\Dorcas\Resources\Invoicing\Product;
use Hostville\Dorcas\Sdk;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /** @var Product */
    protected $resource;

    public function setUp()
    {
        $sdk = new Sdk(['credentials' => ['id' => 1, 'secret' => 'fake-secret-code']]);
        $this->resource = $sdk->createProductResource();
    }

    public function testGetName()
    {
        $this->assertEquals('Product', $this->resource->getName());
    }
}