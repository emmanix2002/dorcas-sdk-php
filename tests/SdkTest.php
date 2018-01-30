<?php

namespace Hostville\Dorcas\Tests;

use GuzzleHttp\Client;
use Hostville\Dorcas\Exception\DorcasException;
use Hostville\Dorcas\Manifest;
use Hostville\Dorcas\Sdk;
use Hostville\Dorcas\UrlRegistry;
use PHPUnit\Framework\TestCase;

class SdkTest extends TestCase
{
    /** @var Sdk */
    protected $sdk;

    public function setUp()
    {
        $this->sdk = new Sdk(['credentials' => ['id' => 1, 'secret' => 'fake-secret-code']]);
    }

    public function testCheckCredentialsFails()
    {
        $this->expectException(DorcasException::class);
        $sdk = new Sdk();
    }

    public function testCheckCredentialsPasses()
    {
        $this->assertInstanceOf(Sdk::class, $this->sdk);
    }

    public function testGetClientId()
    {
        $this->assertEquals(1, $this->sdk->getClientId());
    }

    public function testGetClientSecret()
    {
        $this->assertEquals('fake-secret-code', $this->sdk->getClientSecret());
    }

    public function testGetHttpClient()
    {
        $this->assertInstanceOf(Client::class, $this->sdk->getHttpClient());
    }

    public function testGetManifest()
    {
        $this->assertInstanceOf(Manifest::class, $this->sdk->getManifest());
        $this->assertNotEmpty($this->sdk->getManifest()->data());
    }

    public function testGetUrlRegistry()
    {
        $this->assertInstanceOf(UrlRegistry::class, $this->sdk->getUrlRegistry());
        $this->assertEquals(UrlRegistry::STAGING_URL, (string) $this->sdk->getUrlRegistry()->getUrl());
    }

    public function testGetAuthorizationToken()
    {
        $this->assertEmpty($this->sdk->getAuthorizationToken());
    }

    public function testSetAuthorizationToken()
    {
        $this->sdk->setAuthorizationToken('fake-token-value');
        $this->assertEquals('fake-token-value', $this->sdk->getAuthorizationToken());
    }

    /**
     * @dataProvider resourceProvider
     */
    public function testCreateResource($name, $expected)
    {
        $method = 'create' . $name . 'Resource';
        $resource = $this->sdk->{$method}();
        $this->assertInstanceOf($expected, $resource);
    }

    public function resourceProvider()
    {
        return [
            ['ContactField', \Hostville\Dorcas\Resources\Crm\ContactField::class],
            ['Customer', \Hostville\Dorcas\Resources\Crm\Customer::class],
            ['Order', \Hostville\Dorcas\Resources\Invoicing\Order::class],
            ['Product', \Hostville\Dorcas\Resources\Invoicing\Product::class],
            ['User', \Hostville\Dorcas\Resources\Users\User::class]
        ];
    }

    /**
     * @dataProvider serviceProvider
     */
    public function testCreateService($name, $expected)
    {
        $method = 'create' . $name . 'Service';
        $resource = $this->sdk->{$method}();
        $this->assertInstanceOf($expected, $resource);
    }

    public function serviceProvider()
    {
        return [
            ['PasswordLogin', \Hostville\Dorcas\Services\Identity\PasswordLogin::class],
            ['Registration', \Hostville\Dorcas\Services\Identity\Registration::class]
        ];
    }
}