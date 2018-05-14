<?php

namespace Hostville\Dorcas;


use Hostville\Dorcas\Exception\DorcasException;
use GuzzleHttp\Psr7\Uri;

interface RequestInterface
{
    /**
     * Tells whether or not the resource/service requires the presence of the Authorization header for requests.
     *
     * @return bool
     */
    public function requiresAuthorization(): bool;

    /**
     * Returns the URI instance the request is to be made to.
     *
     * @param array $extras Extra path components to add to the URL
     *
     * @return Uri
     */
    public function getRequestUrl(array $extras = []): Uri;

    /**
     * Returns an associative array containing values that will be used to compose the query parameters.
     *
     * @return array
     */
    public function getQuery(): array;

    /**
     * An associative array containing values that should be passed along in the query string.
     *
     * @param array $params
     *
     * @return RequestInterface
     */
    public function setQuery(array $params = []): RequestInterface;

    /**
     * Allows you to add query parameters one at a time.
     *
     * @param string            $name
     * @param int|float|string  $value     the value for the parameter.
     * @param bool              $overwrite overwrite the value if it already exists in the query
     *
     * @return RequestInterface
     */
    public function addQueryArgument(string $name, $value, bool $overwrite = false): RequestInterface;

    /**
     * Called before the request is sent out. It should be implemented to provide a check on the request, ensuring
     * that all required values are set in the request body.
     *
     * @return bool
     * @throws DorcasException
     */
    public function validate(): bool;
}