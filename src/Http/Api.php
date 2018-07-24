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
class Api
{
    // @var string Actual version of the API.
    const API_VERSION = 'v1';

      // @var string The WeTransfer API base path.
    const API_BASE_URL = 'https://dev.wetransfer.com/' . self::API_VERSION;

    // @var ClientInterface HTTP client.
    static private $http;

    // @var string The WeTransfer API key to be used for requests.
    static private $apiKey;

    // @var string JWT token to be used for requests.
    static private $jwt;

    /**
     * Get the API key to be used for requests.
     *
     * @return string $apiKey
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * Get the JWT token generated after being authenticated.
     *
     * @return string $jwt
     */
    public static function getJWT()
    {
        return self::$jwt;
    }

    /**
     * Sets the JWT to be used for requests.
     *
     * @param string $jwt
     */
    public static function setJWT($jwt)
    {
        if (empty($jwt)) {
            throw new InvalidArgumentException('JWT value cannot be empty.');
        }

        self::$jwt = $jwt;
    }

    /**
     * Makes an HTTP request to the API
     *
     * @param string $method   HTTP method ('get', 'post', etc.)
     * @param string $endpoint Endpoint for the request
     * @param array  $params   List of parameters for the request
     *
     * @return array           Tuple containing (the JSON response, $options)
     */
    public static function request($method, $endpoint, $params = null)
    {
        self::requireHttpClient();

        $response = self::$http->request($method, self::API_BASE_URL . $endpoint, [
            'headers' => self::defaultHeaders(),
            'json' => $params
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Upload some data to provided S3 URL
     *
     * @param string $uploadUrl S3 signed upload URL
     * @param string $data      Data to upload
     *
     * @return array           Tuple containing (the JSON response, $options)
     */
    public static function upload($uploadUrl, $data)
    {
        self::requireHttpClient();

        $response = self::$http->request('PUT', $uploadUrl, [
            'body' => $data
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Sets the client to be used for query the API endpoints
     *
     * @param ClientInterface $client
     */
    public static function setHttpClient(ClientInterface $http)
    {
        self::$http = $http;
    }

    private static function requireHttpClient()
    {
        if (self::$http !== null) {
            return;
        }

        self::setHttpClient(new Client());
    }

    private static function defaultHeaders()
    {
        $defaultHeaders = [
            'x-api-key' => self::$apiKey,
            'Content-Type' => 'application/json'
        ];

        if (self::$jwt !== null) {
            $defaultHeaders['Authorization'] = 'Bearer ' . self::$jwt;
        }

        return $defaultHeaders;
    }
}
