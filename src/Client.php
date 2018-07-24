<?php
namespace WeTransfer;

use InvalidArgumentException;
use UnexpectedValueException;

use GuzzleHttp\ClientInterface;

use WeTransfer\Api\Auth;
use WeTransfer\Http\Api;

/**
 * The WeTransfer API client
 *
 * @package WeTransfer
 */
class Client
{
    /**
     * Sets the API key to be used for requests, and initializes the SDK client
     * It also calls the authorize method, so the client is ready to start doing
     * requests immediately
     *
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        if (empty($apiKey)) {
            throw new InvalidArgumentException('API key value cannot be empty.');
        }

        Api::setApiKey($apiKey);
        return self::authorize();
    }

    /**
     * Autorizes the API key and gets a JWT token
     *
     * @return string Authorization token
     */
    public static function authorize()
    {
        if (Api::getApiKey() == null) {
            throw new UnexpectedValueException('Call \WeTransfer\Client::setApiKey first to set the API key value.');
        }

        $auth = Auth::authorize();
        Api::setJWT($auth['token']);

        return $auth['token'];
    }

    /**
     * Sets the client to be used for query the API endpoints
     *
     * @param ClientInterface $client
     */
    public static function setHttpClient(ClientInterface $http)
    {
        Api::setHttpClient($http);
    }
}
