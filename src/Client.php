<?php
namespace WeTransfer;

use InvalidArgumentException;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;

use WeTransfer\Api\Auth as AuthApi;
use WeTransfer\Api\Transfer as TransferApi;
use WeTransfer\Http\ApiRequest;

/**
 * The WeTransfer API client
 *
 * @package WeTransfer
 */
class Client
{
  // @var string The WeTransfer API key needed to access the API.
  private $apiKey;

  // @var WeTransfer\Http\ApiRequest Request service
  private $api;

  // @var WeTransfer\Api\Transfer Transfer API service
  private $transferApi;

  // @var ClientInterface HTTP client used to make requests.
  private static $http;

  /**
   * Sets the API key to be used for requests.
   *
   * @param string $apiKey
   */
  public function __construct($apiKey)
  {
    if(empty($apiKey)) {
      throw new InvalidArgumentException('API key value cannot be empty.');
    }

    $this->api = new ApiRequest();
    $this->api->setApiKey($apiKey);
    $this->api->setHttpClient(self::getHttpClient());

    $this->authorize();

    $this->createTransferApi();
  }

  public function createTransfer($name, $description = '')
  {
    return $this->transferApi->create($name, $description);
  }

  /**
   * Autorizes the API key and gets a JWT token
   */
  private function authorize()
  {
    $auth = new AuthApi($this->api);
    $token = $auth->authorize()['token'];
    $this->api->setJWT($token);
  }

  private function createTransferApi()
  {
    $this->transferApi = new TransferApi($this->api);
  }

  /**
   * Gets the HTTP client
   *
   * @return $ClientInterface
   */
  public static function getHttpClient()
  {
    if (self::$http === null) {
      self::$http = new HttpClient();
    }

    return self::$http;
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
}
