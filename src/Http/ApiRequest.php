<?php
namespace WeTransfer\Http;

/**
 * This module is responsible to make the final calls to the API.
 *
 * @package WeTransfer\http
 */
final class ApiRequest
{
  // @var string Actual version of the API.
  const API_VERSION = 'v1';

  // @var string The WeTransfer API base path.
  const API_BASE_URL = 'https://dev.wetransfer.com/' . self::API_VERSION;

  // @var string HTTP client.
  private static $http = null;

  // @var string The WeTransfer API key to be used for requests.
  private static $apiKey = null;

  // @var string JWT token to be used for requests.
  private static $jwt = null;

  /**
   * Sets the API key to be used for requests.
   *
   * @param string $apiKey
   */
  public static function setApiKey($apiKey)
  {
    if(empty($apiKey)) {
      throw new InvalidArgumentException('API key value cannot be empty.');
    }

    self::$apiKey = $apiKey;
  }

  /**
   * Gets the API key
   *
   * @return string $apiKey
   */
  public static function getApiKey()
  {
    return self::$apiKey;
  }

  /**
   * Sets the JWT to be used for requests.
   *
   * @param string $jwt
   */
  public static function setJWT($jwt)
  {
    if(empty($jwt)) {
      throw new InvalidArgumentException('JWT value cannot be empty.');
    }

    self::$jwt = $jwt;
  }

  /**
   * Makes an HTTP request to the API
   *
   * @static
   *
   * @param string $method   HTTP method ('get', 'post', etc.)
   * @param string $endpoint Endpoint for the request
   * @param array  $params   List of parameters for the request
   *
   * @return array tuple containing (the JSON response, $options)
   */
  public static function request($method, $endpoint, $params = []) {
    $client = self::httpClient();
    $response = $client->request($method, self::API_BASE_URL . $endpoint, [
      'debug' => true,
      'headers' => self::defaultHeaders(),
      'json' => $params
      ]);

    return json_decode($response->getBody(), true);
  }

  /**
   * Default headers for all HTTP requests
   *
   * @static
   *
   * @return array
   */
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

  /**
   * @return GuzzleHttp\ClientInterface
   */
  private static function httpClient()
  {
    if (!self::$http) {
      self::$http = new \GuzzleHttp\Client();
    }

    return self::$http;
  }
}
