<?php
namespace WeTransfer;

use InvalidArgumentException;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;

use WeTransfer\Api\Auth as AuthApi;
use WeTransfer\Api\Transfer as TransferApi;
use WeTransfer\Api\Links as LinksApi;
use WeTransfer\Api\Files as FilesApi;
use WeTransfer\Entity\Transfer as TransferEntity;
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

  // @var WeTransfer\Api\Link Link API service
  private $linksApi;

  // @var WeTransfer\Api\Files Files API service
  private $filesApi;

  // @var ClientInterface HTTP client used to make requests.
  private static $http;

  /**
   * Sets the API key to be used for requests, and initializes the SDK client
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
    $this->createLinksApi();
    $this->createFilesApi();
  }

  /**
   * Creates a new transfer given a name and a description
   *
   * @param string $name        Name of the transfer
   * @param string $description Optional descritpion for the transfer
   *
   * @return Transfer $transfer
   */
  public function createTransfer($name, $description = '')
  {
    return $this->transferApi->create($name, $description);
  }

  /**
   * Adds links to an existing transfer
   *
   * @param Transfer $transfer An existing transfer instance
   * @param array    $links    A list of links to be added to the transfer
   *
   * @return Transfer $transfer
   */
  public function addLinks(TransferEntity $transfer, array $links = [])
  {
    return $this->linksApi->addLinks($transfer, $links);
  }

  /**
   * Adds files to an existing transfer
   *
   * @param Transfer $transfer An existing transfer instance
   * @param array    $files    A list of files to be added to the transfer
   *
   * @return Transfer $transfer
   */
  public function addFiles(TransferEntity $transfer, array $files = [])
  {
    return $this->filesApi->addFiles($transfer, $files);
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

  private function createLinksApi()
  {
    $this->linksApi = new LinksApi($this->api);
  }

  private function createFilesApi()
  {
    $this->filesApi = new FilesApi($this->api);
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
