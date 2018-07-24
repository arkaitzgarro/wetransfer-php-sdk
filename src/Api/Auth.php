<?php
namespace WeTransfer\Api;

use WeTransfer\Http\Api;

/**
 * Authorize the API key
 *
 * @package WeTransfer\Api
 */
class Auth
{
    /**
     * Authorize the existing API key
     *
     * @return GuzzleHttp\Psr7\Response
     */
    public static function authorize()
    {
        return Api::request('POST', '/authorize');
    }
}
