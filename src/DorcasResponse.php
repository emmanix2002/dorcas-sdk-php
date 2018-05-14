<?php

namespace Hostville\Dorcas;


use Psr\Http\Message\RequestInterface;

class DorcasResponse
{
    /** @var string */
    private $rawResponse;

    /** @var int  */
    private $httpStatus;

    /** @var array */
    private $data = [];

    /** @var RequestInterface  */
    private $request;

    /** @var bool  */
    public $asObjects = false;

    /**
     * DorcasResponse constructor.
     *
     * @param string                $response
     * @param int                   $httpStatus
     * @param RequestInterface|null $request
     */
    public function __construct(string $response, int $httpStatus = 200, RequestInterface $request = null)
    {
        $this->httpStatus = $httpStatus;
        $this->request = $request;
        $this->rawResponse = $response;
        $jsonData = $this->decodeJson($response);
        $this->data = $jsonData ?: [];
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        return array_key_exists($name, $this->data) ? $this->data[$name] : null;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Attempts to decode a JSON string to an array; if it fails, it just returns an array with the data index as the
     * string.
     *
     * @param string $jsonString
     *
     * @return array
     */
    private function decodeJson(string $jsonString)
    {
        $json = json_decode($jsonString, true);
        return $json ?: ['data' => $jsonString];
    }

    /**
     * Returns the raw response that was passed into the constructor.
     *
     * @return string
     */
    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }

    /**
     * Is it a success response?
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->httpStatus >= 200 && $this->httpStatus < 300;
    }

    /**
     * The response code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->data['code'] ?? '';
    }
    /**
     * The response message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->data['message'] ?? '';
    }

    /**
     * Returns the value of the "data" key in the response if available, else it returns the parsed response.
     *
     * @param bool $asObject
     *
     * @return array|mixed|object
     */
    public function getData(bool $asObject = false)
    {
        $data = $this->data['data'] ?? $this->data;
        return $asObject && $data !== null ? (object) $data : $data;
    }

    /**
     * Returns the errors in the response, if any.
     *
     * @param bool $asObject
     *
     * @return array|mixed|object
     */
    public function getErrors(bool $asObject = false)
    {
        $errors = $this->data['errors'] ?? [];
        return $asObject ? (object) $errors : $errors;
    }

    /**
     * Returns a summary of the request. This will usually be available in the case of a failure.
     *
     * @return array
     */
    public function dumpRequest(): array
    {
        if (empty($this->request)) {
            return ['http_status' => $this->httpStatus, 'response' => $this->data];
        }
        $this->request->getBody()->rewind();
        # rewind the request
        $size = $this->request->getBody()->getSize() ?: 1024;
        # get the size, we need it to be able to read through; else we assume 1024
        $bodyParams = json_decode($this->request->getBody()->read($size), true);
        # the body parameters
        $possibleJsonString = (string) $this->request->getBody();
        # cast it to a string
        $jsonData = json_decode($possibleJsonString);
        $uri = $this->request->getUri();
        $url = $uri->getScheme() . '://' . $uri->getAuthority() . '/' . $uri->getPath();
        return [
            'http_status' => $this->httpStatus,
            'endpoint' => $url,
            'params'   => $bodyParams,
            'response' => $jsonData ?: $possibleJsonString
        ];
    }
}