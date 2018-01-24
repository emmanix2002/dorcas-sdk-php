<?php

namespace Dorcas;


use Dorcas\Exception\DorcasException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;

trait SendsHttpRequestTrait
{
    /** @var array  */
    protected $headers = [];

    /** @var array  */
    protected $body = [];

    /**
     * @inheritdoc
     */
    public function requiresAuthorization(): bool
    {
        return false;
    }

    /**
     * The value for the Authorization header.
     *
     * @return string
     */
    public function getAuthorizationHeader(): string
    {
        return '';
    }

    /**
     * Does the request carry JSON data?
     *
     * @return bool
     */
    public function isJsonRequest(): bool
    {
        return true;
    }

    /**
     * The request URL.
     *
     * @return Uri
     */
    public function getRequestUrl(): Uri
    {
        return new Uri();
    }

    /**
     * Pre-fills the request header with some default values as required.
     *
     * @return $this
     */
    protected function prefillHeader()
    {
        if ($this->requiresAuthorization()) {
            $this->headers['Authorization'] = $this->getAuthorizationHeader();
        }
        return $this;
    }

    /**
     * Pre-fills the request body with whatever data is required.
     * This method should be overridden to customise what should be placed into the body by default.
     * This applies to DELETE, POST, and PUT requests, allows you to set the payload
     *
     * @return $this
     */
    protected function prefillBody()
    {
        return $this;
    }

    /**
     * Adds a parameter to the body of the request.
     *
     * @param string $name
     * @param        $value
     * @param bool   $overwrite
     *
     * @return $this
     */
    public function addBodyParam(string $name, $value, bool $overwrite = false)
    {
        if (array_key_exists($name, $this->body) && !$overwrite) {
            return $this;
        }
        if (!is_array($value) && !is_scalar($value)) {
            throw new \InvalidArgumentException(
                'The value for a parameter should either be a scalar type (int, string, float), or an array.'
            );
        }
        $this->body[$name] = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        throw new DorcasException('You should override this method.');
    }

    /**
     * Sends a HTTP request.
     *
     * @param string $method
     * @param Client $httpClient
     * @param array  $path          additional components for the path; e.g.: [$id, 'prices']
     *
     * @return DorcasResponse
     */
    public function send(string $method, Client $httpClient, array $path = []): DorcasResponse
    {
        $this->prefillHeader();
        $this->prefillBody();
        if (strtolower($method) !== 'get') {
            # we don't validate GEt requests
            $this->validate();
        }
        if ($this->requiresAuthorization()) {
            $headers['Authorization'] = $this->getAuthorizationHeader();
        }
        $uri = static::getRequestUrl($path);
        $url = $uri->getScheme() . '://' . $uri->getAuthority() . $uri->getPath();
        # set the URL
        try {
            $options = [];
            # the request data
            if (!empty($this->headers)) {
                $options[RequestOptions::HEADERS] = $this->headers;
            }
            if (!empty($uri->getQuery())) {
                # some query parameters are present in the URL
                $options[RequestOptions::QUERY] = parse_query_parameters($uri->getQuery());
            }
            if (strtolower($method) !== 'get') {
                # not a get request
                if (static::isJsonRequest() && !empty($this->body)) {
                    # a JSON request
                    $options[RequestOptions::JSON] = $this->body;

                } elseif (!empty($this->body)) {
                    # we switch to an application/www-form-urlencoded type
                    $options[RequestOptions::FORM_PARAMS] = $this->body;
                }
            }
            $response = $httpClient->request($method, $url, $options);
            return new DorcasResponse((string) $response->getBody());

        } catch (BadResponseException $e) {
            // in the case of a failure, let's know the status
            return new DorcasResponse((string) $e->getResponse()->getBody(), $e->getResponse()->getStatusCode(), $e->getRequest());

        } catch (ConnectException $e) {
            return new DorcasResponse('{"status": "error", "data": "'.$e->getMessage().'"}', 0);
        }
    }
}