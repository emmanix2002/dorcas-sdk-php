<?php

namespace Hostville\Dorcas\Tests;


use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function testIsInstalledViaComposer()
    {
        $this->assertFalse(is_installed_via_composer());
    }

    public function testAppPath()
    {
        $root = dirname(__DIR__);
        $this->assertContains($root, dorcas_sdk_app_path());
    }

    public function testHttpClient()
    {
        $this->assertInstanceOf(Client::class, http_client());
    }

    public function testLoadManifest()
    {
        $this->assertArrayHasKey('resources', load_manifest());
    }

    public function testParseQueryParameters()
    {
        $query = ['a' => 1, 'b' => 2, 'c' => 3];
        $this->assertCount(count($query), parse_query_parameters(http_build_query($query)));
    }
}