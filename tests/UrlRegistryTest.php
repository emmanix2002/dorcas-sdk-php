<?php

namespace Hostville\Dorcas\Tests;


use Hostville\Dorcas\UrlRegistry;
use PHPUnit\Framework\TestCase;

class UrlRegistryTest extends TestCase
{

    public function testProductionUrlIsCorrect()
    {
        $registry = new UrlRegistry('production');
        $this->assertEquals(UrlRegistry::ENVIRONMENTS['production'], (string) $registry->getUrl());
    }

    public function testStagingUrlIsCorrect()
    {
        $registry = new UrlRegistry('staging');
        $this->assertEquals(UrlRegistry::ENVIRONMENTS['staging'], (string) $registry->getUrl());
    }

    public function testIsProduction()
    {
        $registry = new UrlRegistry('production');
        $this->assertTrue($registry->isProduction());
    }

    public function testGetUrlWithPath()
    {
        $registry = new UrlRegistry('staging');
        $path = [1, 'contacts'];
        $expected = UrlRegistry::ENVIRONMENTS['staging'] . '/customers/' . implode('/', $path);
        $this->assertEquals($expected, (string) $registry->getUrl('customers', ['path' => $path]));
    }

    public function testGetUrlWithQuery()
    {
        $registry = new UrlRegistry('staging');
        $query = ['a' => 1, 'b' => 2];
        $expected = UrlRegistry::ENVIRONMENTS['staging'] . '?' . http_build_query($query);
        $this->assertEquals($expected, (string) $registry->getUrl(null, ['query' => $query]));
    }

    public function testGetUrlWithPathAndQuery()
    {
        $registry = new UrlRegistry('staging');
        $path = [1, 'contacts'];
        $query = ['a' => 1, 'b' => 2];
        $expected = UrlRegistry::ENVIRONMENTS['staging'] . '/customers/' . implode('/', $path) . '?' . http_build_query($query);
        $this->assertEquals($expected, (string) $registry->getUrl('customers', ['path' => $path, 'query' => $query]));
    }
}