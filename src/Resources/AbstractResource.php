<?php

namespace Hostville\Dorcas\Resources;


use Hostville\Dorcas\DorcasResponse;
use Hostville\Dorcas\RequestInterface;
use Hostville\Dorcas\Sdk;
use Hostville\Dorcas\SendsHttpRequestTrait;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Arr;

abstract class AbstractResource implements ResourceInterface
{
    use SendsHttpRequestTrait {
        send as httpSend;
    }

    /** @var null|string  */
    protected $id = null;

    /** @var array  */
    protected $relationships;

    /** @var Sdk  */
    protected $sdk;

    /** @var array */
    protected $query;

    /**
     * AbstractResource constructor.
     *
     * @param Sdk         $sdk
     * @param string|null $id
     */
    public function __construct(Sdk $sdk, string $id = null)
    {
        $this->sdk = $sdk;
        $this->id = $id;
        $this->relationships = [];
        $this->query = [];
    }

    /**
     * @inheritdoc
     */
    public function requiresAuthorization(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getAuthorizationHeader(): string
    {
        if (empty($this->sdk->getAuthorizationToken())) {
            return '';
        }
        return 'Bearer ' . $this->sdk->getAuthorizationToken();
    }

    /**
     * @inheritdoc
     */
    public function item(string $id): ResourceInterface
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function collection(): ResourceInterface
    {
        $this->id = null;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function relationships(...$relationships): ResourceInterface
    {
        $includes = $this->sdk->getManifest()->getResource(static::getName(), "includes");
        # get the resource entry from the manifest
        if (empty($includes)) {
            return $this;
        }
        if (count($relationships) === 1 && is_array($relationships[0])) {
            # we have only one entry in the array, but that entry is another array
            $relationships = $relationships[0];
        }
        foreach ($relationships as $relation => $params) {
            # we loop through the relationships
            if (is_int($relation)) {
                # this is an array index, not a string key
                $relation = $params;
                $params = [];
            }
            $this->relationships[strtolower($relation)] = $this->parseRelationshipParams($params);
        }
        return $this;
    }

    /**
     * Processes the parameters passed for the relationship.
     *
     * @param array $params
     *
     * @return array
     */
    protected function parseRelationshipParams(array $params): array
    {
        if (empty($params)) {
            return [];
        }
        $parsed = [];
        foreach ($params as $key => $value) {
            # loop through
            if (is_int($key)) {
                $parsed[$key] = '';
                continue;
            }
            if ($key !== 'paginate' || ($key === 'paginate' && !Arr::isAssoc($value))) {
                $key = $key === 'paginate' ? 'limit' : $key;
                $parsed[$key] = implode('|', $value);

            } else {
                $limit = $this->findInArray($value, 'limit', 0, 10);
                $offset = $this->findInArray($value, 'offset', 1, 0);
                $parsed['limit'] = $limit . '|' . $offset;
            }
        }
        return $parsed;
    }

    /**
     * Searches the array for a value based on a key, a default index, else it returns the default value.
     *
     * @param array  $target
     * @param string $key
     * @param int    $defaultIndex
     * @param null   $defaultValue
     *
     * @return mixed|null
     */
    private function findInArray(array $target, string $key, int $defaultIndex = 0, $defaultValue = null)
    {
        if (empty($target)) {
            return $defaultValue;
        }
        if (!empty($target[$key])) {
            return $target[$key];
        } elseif (!empty($target[$defaultIndex])) {
            return $target[$defaultIndex];
        }
        return $defaultValue;
    }

    /**
     * @inheritdoc
     */
    public function getRequestUrl(array $extras = []): Uri
    {
        $path = $this->sdk->getManifest()->getResource(static::getName(), 'path');
        # get the path for the resource
        if (!empty($this->id)) {
            # requesting data off a resource item
            $path .= '/' . $this->id;
        }
        $base = $this->sdk->getUrlRegistry()->getUrl($path, ['path' => $extras, 'query' => $this->getQuery()]);
        # compose the full request URL
        return $base;
    }

    /**
     * @inheritdoc
     */
    public function addQueryArgument(string $name, $value, bool $overwrite = false): RequestInterface
    {
        if (array_key_exists($name, $this->query) && !$overwrite) {
            return $this;
        }
        if (is_null($value)) {
            unset($this->query[$name]);
            return $this;
        }
        $this->query[$name] = $value;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setQuery(array $params = []): RequestInterface
    {
        $this->query = $params;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getQuery(): array
    {
        $query = [];
        if (!empty($this->relationships)) {
            $includes = [];
            foreach ($this->relationships as $relation => $params) {
                $include = $relation;
                if (!empty($params)) {
                    $arguments = [];
                    foreach ($params as $name => $value) {
                        $arguments[] = $name . '(' . $value . ')';
                    }
                    $include .= ':' . implode(':', $arguments);
                }
                $includes[] = $include;
            }
            $query['include'] = implode(',', $includes);
        }
        return array_merge($query, $this->query);
    }

    /**
     * @inheritdoc
     */
    public function validate(): bool
    {
        return true;
    }
    
    /**
     * @param string $method
     * @param array  $path
     *
     * @return \Hostville\Dorcas\DorcasResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(string $method, array $path = []): DorcasResponse
    {
        return $this->httpSend($method, $this->sdk->getHttpClient(), $path);
    }

    /**
     * Returns the name of the resource.
     *
     * @return string
     */
    abstract function getName(): string;
}