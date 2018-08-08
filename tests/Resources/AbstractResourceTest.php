<?php

namespace Hostville\Dorcas\Tests\Resources;


use Hostville\Dorcas\Resources\Crm\Customer;
use Hostville\Dorcas\Resources\ResourceInterface;
use Hostville\Dorcas\Sdk;
use PHPUnit\Framework\TestCase;

class AbstractResourceTest extends TestCase
{
    /** @var Customer */
    protected $resource;

    public function setUp()
    {
        $sdk = new Sdk(['credentials' => ['id' => 1, 'secret' => 'fake-secret-code']]);
        $this->resource = $sdk->createCustomerResource();
    }

    public function testRequiresAuthorization()
    {
        $this->assertTrue($this->resource->requiresAuthorization());
    }

    public function testGetAuthorizationHeader()
    {
        $this->assertEquals('', $this->resource->getAuthorizationHeader());
    }

    public function testIsJsonRequest()
    {
        $this->assertTrue($this->resource->isJsonRequest());
    }

    public function testItem()
    {
        $resource = (clone $this->resource)->item('1234567890');
        $url = (string) $resource->getRequestUrl();
        $this->assertEquals('1234567890', substr($url, -10));
        return $resource;
    }

    /**
     * @depends testItem
     */
    public function testCollection(ResourceInterface $resource)
    {
        $resource->collection();
        $url = (string) $resource->getRequestUrl();
        $this->assertNotContains('1234567890', $url);
    }

    public function testSetQuery()
    {
        $resource = (clone $this->resource)->setQuery(['a' => 1, 'b' => 2]);
        $this->assertEquals(['a' => 1, 'b' => 2], $resource->getQuery());
    }

    public function testAddQueryArgument()
    {
        $resource = (clone $this->resource)->addQueryArgument('limit', 10, true);
        $this->assertArrayHasKey('limit', $resource->getQuery());
        $value = $resource->getQuery()['limit'] ?? 0;
        $this->assertEquals(10, $value);
    }

    public function testRelationships()
    {
        $resource = (clone $this->resource)->relationships("company", "contacts", "orders");
        $this->assertArrayHasKey('include', $resource->getQuery());
        $this->assertContains('company', $resource->getQuery()['include']);
    }

    public function testGetUrl()
    {
        $resource = (clone $this->resource)->item('1234567890');
        $url = $resource->getRequestUrl();
        $this->assertEquals('/customers/1234567890', $url->getPath());
        $resource->collection();
        $this->assertEquals('/customers', $resource->getRequestUrl()->getPath());
    }
}