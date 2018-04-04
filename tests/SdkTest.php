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
        $this->assertEquals(UrlRegistry::ENVIRONMENTS['staging'], (string) $this->sdk->getUrlRegistry()->getUrl());
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
            ['Company', \Hostville\Dorcas\Resources\Company::class],
            ['Country', \Hostville\Dorcas\Resources\Common\Country::class],
            ['ContactField', \Hostville\Dorcas\Resources\Crm\ContactField::class],
            ['Customer', \Hostville\Dorcas\Resources\Crm\Customer::class],
            ['Department', \Hostville\Dorcas\Resources\Common\Company\Department::class],
            ['Employee', \Hostville\Dorcas\Resources\Common\Company\Employee::class],
            ['Integration', \Hostville\Dorcas\Resources\Common\Company\Integration::class],
            ['Location', \Hostville\Dorcas\Resources\Common\Company\Location::class],
            ['Order', \Hostville\Dorcas\Resources\Invoicing\Order::class],
            ['Partner', \Hostville\Dorcas\Resources\Partner::class],
            ['Plan', \Hostville\Dorcas\Resources\Plan::class],
            ['Product', \Hostville\Dorcas\Resources\Invoicing\Product::class],
            ['State', \Hostville\Dorcas\Resources\Common\State::class],
            ['Team', \Hostville\Dorcas\Resources\Common\Company\Team::class],
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
            ['Company', \Hostville\Dorcas\Services\Identity\Company::class],
            ['Metrics', \Hostville\Dorcas\Services\Metrics::class],
            ['PasswordLogin', \Hostville\Dorcas\Services\Identity\PasswordLogin::class],
            ['Profile', \Hostville\Dorcas\Services\Identity\Profile::class],
            ['Registration', \Hostville\Dorcas\Services\Identity\Registration::class]
        ];
    }
}