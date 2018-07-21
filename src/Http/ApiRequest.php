<?php
namespace WeTransfer\Http;

use InvalidArgumentException;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * This module is responsible to make the final calls to the API.
 *
 * @package WeTransfer\Http
 */
class ApiRequest
{
  // @var string Actual version of the API.
    const API_VERSION = 'v1';

  // @var string The WeTransfer API base path.
    const API_BASE_URL = 'https://dev.wetransfer.com/' . self::API_VERSION;

  // @var ClientInterface HTTP client.
    private $http;

  // @var string The WeTransfer API key to be used for requests.
    private $apiKey;

  // @var string JWT token to be used for requests.
    private $jwt;

  /**
   * Sets the API key to be used for requests.
   *
   * @param string $apiKey
   */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

  /**
   * Sets the JWT to be used for requests.
   *
   * @param string $jwt
   */
    public function setJWT($jwt)
    {
        if (empty($jwt)) {
            throw new InvalidArgumentException('JWT value cannot be empty.');
        }

        $this->jwt = $jwt;
    }

  /**
   * Makes an HTTP request to the API
   *
   * @param string $method   HTTP method ('get', 'post', etc.)
   * @param string $endpoint Endpoint for the request
   * @param array  $params   List of parameters for the request
   *
   * @return array tuple containing (the JSON response, $options)
   */
    public function request($method, $endpoint, $params = null)
    {
        $response = $this->http->request($method, self::API_BASE_URL . $endpoint, [
        'headers' => $this->defaultHeaders(),
        'json' => $params
        ]);

        return json_decode($response->getBody(), true);
    }

  /**
   * Upload some data to provided S3 URL
   *
   * @param string $uploadUrl S3 signed upload URL
   * @param string $data      Data to upload
   */
    public function upload($uploadUrl, $data)
    {
        $response = $this->http->request('PUT', $uploadUrl, [
        'body' => $data
        ]);

        return json_decode($response->getBody(), true);
    }

  /**
   * Sets the client to be used for query the API endpoints
   *
   * @param ClientInterface $client
   *
   * @return $this
   */
    public function setHttpClient(ClientInterface $http = null)
    {
        if ($http === null) {
            $http = new Client();
        }

        $this->http = $http;
        return $this;
    }

  /**
   * Default headers for all HTTP requests
   *
   * @return array
   */
    private function defaultHeaders()
    {
        $defaultHeaders = [
        'x-api-key' => $this->apiKey,
        'Content-Type' => 'application/json'
        ];

        if ($this->jwt !== null) {
            $defaultHeaders['Authorization'] = 'Bearer ' . $this->jwt;
        }

        return $defaultHeaders;
    }
}
