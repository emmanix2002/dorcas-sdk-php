<?php /** @noinspection ALL */

namespace Hostville\Dorcas;


use Hostville\Dorcas\Exception\DorcasException;
use Hostville\Dorcas\Exception\ResourceNotFoundException;
use Hostville\Dorcas\Resources\ResourceInterface;
use Hostville\Dorcas\Services\ServiceInterface;
use GuzzleHttp\Client;

/**
 * The main SDK class for accessing the resources, and services on the Dorcas API.
 * It provides some methods that allow you to easily create, and use resources and services.
 *
 *
 * @method \Hostville\Dorcas\Resources\ECommerce\Advert             createAdvertResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\ECommerce\Blog               createBlogResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Company                      createCompanyResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Coupon                       createCouponResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Invite                       createInviteResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Partner                      createPartnerResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Plan                         createPlanResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Common\Country               createCountryResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Common\State                 createStateResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Common\Company\Department    createDepartmentResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Common\Directory             createDirectoryResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Common\Company\Employee      createEmployeeResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Common\Company\Integration   createIntegrationResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Common\Company\Location      createLocationResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Common\Company\Team          createTeamResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Crm\ContactField             createContactFieldResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Crm\Customer                 createCustomerResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Crm\Deal                     createDealResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Crm\Group                    createGroupResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Developers\Developer         createDeveloperResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Developers\Application       createDeveloperApplicationResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Developers\AppStore          createAppStoreResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\ECommerce\Domain             createDomainResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Finance\Finance              createFinanceResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Invoicing\Order              createOrderResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Invoicing\Product            createProductResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Invoicing\ProductCategory    createProductCategoryResource(string $id = null)
 * @method \Hostville\Dorcas\Resources\Users\User                   createUserResource(string $id = null)
 * @method \Hostville\Dorcas\Services\Identity\Authorization        createAuthorizationService()
 * @method \Hostville\Dorcas\Services\Identity\Company              createCompanyService()
 * @method \Hostville\Dorcas\Services\Metrics                       createMetricsService()
 * @method \Hostville\Dorcas\Services\Identity\PasswordLogin        createPasswordLoginService()
 * @method \Hostville\Dorcas\Services\Identity\Profile              createProfileService()
 * @method \Hostville\Dorcas\Services\Identity\Registration         createRegistrationService()
 * @method \Hostville\Dorcas\Services\Store                         createStoreService()
 *
 */
class Sdk
{
    const VERSION = '0.0.1';

    /**
     * The configuration options to be used throughout the Sdk.
     *
     * @var array
     */
    private $args;

    /**
     * @var UrlRegistry
     */
    private $urlRegistry;

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * @var Manifest
     */
    private $manifest;

    /** @var string|null */
    private $token;

    /**
     * Sdk constructor.
     *
     * - environment: the usage environment, it can be either "staging", or "production".
     *   Any value that isn't "production" is assumed to be "staging".
     * - credentials: an associative array of that contains the "id", "secret", and "token" keys.
     *   These represent the application client id and client secret generated for the request application; while the
     *   "token" key holds the value for the returned Bearer token from a successful authorization request.
     *
     * @param array $args Requires certain keys to be set for a proper configuration.
     */
    public function __construct(array $args = [])
    {
        if (empty($args['environment'])) {
            $args['environment'] = 'staging';
        }
        $this->checkCredentials($args);
        $this->args = $args;
        $this->urlRegistry = new UrlRegistry($args['environment']);
        $this->httpClient = http_client();
        $this->manifest = new Manifest();
        $this->token = data_get($args, 'credentials.token', null);
    }

    /**
     * Returns the OAuth client id.
     *
     * @return string
     */
    public function getClientId(): string
    {
        return (string) data_get($this->args, 'credentials.id');
    }

    /**
     * Returns the OAuth client secret.
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return (string) data_get($this->args, 'credentials.secret');
    }

    /**
     * Returns the HTTP client in use by the Sdk.
     *
     * @return Client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * Returns the loaded manifest.
     *
     * @return Manifest
     */
    public function getManifest(): Manifest
    {
        return $this->manifest;
    }

    /**
     * Returns the instance.
     *
     * @return UrlRegistry
     */
    public function getUrlRegistry(): UrlRegistry
    {
        return $this->urlRegistry;
    }

    /**
     * Returns the authorization token value.
     *
     * @return string
     */
    public function getAuthorizationToken(): string
    {
        return (string) $this->token;
    }

    /**
     * Sets the authorization token.
     *
     * @param string $token
     *
     * @return Sdk
     */
    public function setAuthorizationToken(string $token): Sdk
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Checks the credentials configuration to make sure it is valid.
     *
     * @param array $args
     *
     * @return bool
     * @throws DorcasException
     */
    private function checkCredentials(array $args = []): bool
    {
        if (empty($args['credentials'])) {
            throw new DorcasException('You did not provide the Dorcas client credentials in the configuration.', $args);
        }
        $id = data_get($args, 'credentials.id', null);
        $secret = data_get($args, 'credentials.secret', null);
        if (empty($id)) {
            throw new DorcasException('The client "id" key is absent in the credentials configuration.', $args);
        }
        if (empty($secret)) {
            throw new DorcasException('The client "secret" key is absent in the credentials configuration.', $args);
        }
        return true;
    }

    /**
     * Creates a new resource client with the provided options.
     *
     * @param string $name
     * @param array  $options
     *
     * @return ResourceInterface
     */
    protected function createResourceClient(string $name, array $options = []): ResourceInterface
    {
        $entry = $this->manifest->getResource($name);
        # we check for the manifest entry
        
        if (empty($entry)) {
            throw new ResourceNotFoundException('Could not find the client for the requested resource '.$name);
        }
        $resource = $entry['namespace'] . '\\' . $entry['client'];
        return new $resource($this, ...$options);
    }

    /**
     * Creates a new service client with the provided options.
     *
     * @param string $name
     * @param array  $options
     *
     * @return ServiceInterface
     */
    protected function createServiceClient(string $name, array $options = []): ServiceInterface
    {
        $entry = $this->manifest->getService($name);
        # we check for the manifest entry
        if (empty($entry)) {
            throw new ResourceNotFoundException('Could not find the client for the requested service '.$name);
        }
        $service = $entry['namespace'] . '\\' . $entry['client'];
        return new $service($this, $options);
    }

    /**
     * Magic method.
     *
     * @param $name
     * @param $arguments
     *
     * @return ResourceInterface|ServiceInterface
     */
    public function __call($name, $arguments = null)
    {
        $isCreate = strpos($name, 'create') === 0;
        # check the action type
        if ($isCreate && strtolower(substr($name, -8)) === 'resource') {
            # we're attempting to create a resource client
            $name = substr($name, 6, -8);
            return $this->createResourceClient($name, $arguments);
        } elseif ($isCreate && strtolower(substr($name, -7)) === 'service') {
            # we're attempting to create a service client
            $name = substr($name, 6, -7);
            return $this->createServiceClient($name, $arguments);
        }
        throw new \BadMethodCallException('The method '.$name.' does not exist.');
    }
}