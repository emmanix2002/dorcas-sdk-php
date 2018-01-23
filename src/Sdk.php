<?php

namespace Dorcas;


use Dorcas\Resources\ResourceInterface;
use GuzzleHttp\Client;

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
     * @var array
     */
    private $manifest;

    /**
     * Sdk constructor.
     * - environment: the usage environment, it can be either "staging", or "production".
     *   Any value that isn't "production" is assumed to be "staging".
     * - credentials: an associative array of that contains the "id", and "secret" keys.
     *   These represent the application client id and client secret generated for the request application.
     *
     * @param array $args Requires certain keys to be set for a proper configuration.
     */
    public function __construct(array $args = [])
    {
        if (empty($args['environment']) || strtolower($args['environment']) !== 'production') {
            $args['environment'] = 'staging';
        }
        $this->checkCredentials($args);
        $this->args = $args;
        $this->urlRegistry = new UrlRegistry($args['environment']);
        $this->httpClient = http_client();
        $this->manifest = load_manifest();
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
     * Checks the credentials configuration to make sure it is valid.
     *
     * @param array $args
     *
     * @return bool
     */
    private function checkCredentials(array $args = []): bool
    {
        if (empty($args['credentials'])) {
            throw new \RuntimeException('You did not provide the Dorcas client credentials in the configuration.');
        }
        $id = data_get($args, 'credentials.id', null);
        $secret = data_get($args, 'credentials.secret', null);
        if (empty($id)) {
            throw new \RuntimeException('The client "id" key is absent in the credentials configuration.');
        }
        if (empty($secret)) {
            throw new \RuntimeException('The client "secret" key is absent in the credentials configuration.');
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
        $resource = 'Dorcas\\Resources\\'.$name;
        return new $resource($this, $options);
    }

    protected function createServiceClient(string $name, array $options = [])
    {

    }

    public function __call($name, $arguments)
    {
        $isCreate = strpos($name, 'create') === 0;
        # check the action type
        if ($isCreate && strtolower(substr($name, -8)) === 'resource') {
            # we're attempting to create a resource client
            return $this->createResourceClient($name, $arguments[0] ?? []);
        } elseif ($isCreate && strtolower(substr($name, -7)) === 'service') {
            # we're attempting to create a service client
            return $this->createServiceClient($name, $arguments[0] ?? []);
        }
        throw new \BadMethodCallException('The method '.$name.' does not exist.');
    }
}