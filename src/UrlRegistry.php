<?php

namespace Hostville\Dorcas;


use GuzzleHttp\Psr7\Uri;

class UrlRegistry
{
    /**
     * @var string
     */
    const STAGING_URL = 'http://api.dorcas.local';

    /**
     * @var string
     */
    const PRODUCTION_URL = 'https://api.dorcas.com';

    /**
     * @var string
     */
    private $environment = 'staging';

    /**
     * @var Uri
     */
    protected $uri;


    public function __construct(string $env = 'staging')
    {
        $this->environment = strtolower($env) !== 'production' ? 'staging' : 'production';
        $base = $this->isProduction() ? self::PRODUCTION_URL : self::STAGING_URL;
        $this->uri = new Uri($base);
    }

    /**
     * Is it in production mode?
     *
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this->environment === 'production';
    }

    /**
     * Checks the $params array for additional data to be added to the path.
     *
     * @param array $params must contain the key 'path', with value of type string | string[]
     *
     * @return string
     */
    protected function getPathParams(array $params = []): string
    {
        if (empty($params)) {
            return '';
        }
        $path = $params['path'] ?? [];
        if (is_string($path)) {
            return $path;
        }
        $path = collect($path)->map(function ($entry) {
            return (string) $entry;
        })->all();
        return implode('/', $path);
    }

    /**
     * Checks the $params array for additional data to be used in composing the query part of the URL.
     *
     * @param array $params must contain the key 'query', with value of type string | string[]
     *
     * @return string
     */
    protected function getQueryParams(array $params = []): string
    {
        if (empty($params)) {
            return '';
        }
        $query = $params['query'] ?? [];
        if (is_string($query)) {
            return $query;
        }
        return empty($query) ? '' : http_build_query($query);
    }

    /**
     * Performs the path, and query parameter processing before returning the final path.
     *
     * @param string $path      the base path
     * @param array  $params    contains additional data to be used in composing the path and/or query of the URL
     *
     * @return Uri
     */
    public function getUrl(string $path = null, array $params = []): Uri
    {
        $pathParams = $this->getPathParams($params);
        # get the path parameters
        $queryParams = $this->getQueryParams($params);
        # get the query parameters
        $path .= !empty($pathParams) ? '/' . $pathParams : '';
        # append the rest of the path
        if (!empty($path) && $path[0] !== '/') {
            $path = '/' . $path;
        }
        return $this->uri->withPath($path)->withQuery($queryParams);
    }
}