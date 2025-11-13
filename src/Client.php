<?php

declare( strict_types = 1 );

namespace Ocolin\Calix\Axos;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    public HTTP $http;

/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param string|null $url Base URL of AXOS server.
     * @param string|null $username Basic Auth username.
     * @param string|null $password Basic Auth password.
     * @param int $timeout HTTP timeout. Default = 20 seconds.
     * @param bool $verify Verify SSL connection. Default off.
     */
    public function __construct(
        ?string $url = null,
        ?string $username = null,
        ?string $password = null,
        int $timeout = 20,
        bool $verify = false,
    )
    {
        $this->http = new HTTP(
                 url: $url,
            username: $username,
            password: $password,
             timeout: $timeout,
              verify: $verify,
        );
    }


/* API RESPONSE BODY
----------------------------------------------------------------------------- */

    /**
     * @param string $path API end point path.
     * @param string $method API HTTP method to use.
     * @param string[]|object|null $query Path and Query parameters.
     * @param string[]|object|null $body Body parameters for POST/PUT.
     * @return mixed Output of API service response.
     * @throws GuzzleException
     */
    public function call(
        string $path,
        string $method = 'GET',
        array|object|null $query = null,
        array|object|null $body = null,
    ) : mixed
    {
        $output = $this->full(
              path: $path,
            method: $method,
             query: $query,
              body: $body,
        );

        return $output->body;
    }



/* API FULL CALL
----------------------------------------------------------------------------- */

    /**
     * @param string $path
     * @param string $method
     * @param string[]|object|null $query
     * @param string[]|object|null $body
     * @return Response
     * @throws GuzzleException
     */
    public function full(
        string $path,
        string $method = 'GET',
        array|object|null $query = null,
        array|object|null $body = null,
    ) : Response
    {
        $method = strtoupper( string: $method );

        return match( $method )
        {
            'POST' => self::format_Response(
                 response: $this->http->post(
                     path: $path,
                    query: $query,
                     body: $body,
                )
            ),
            'PUT' => self::format_Response(
                 response: $this->http->put(
                     path: $path,
                    query: $query,
                     body: $body,
                )
            ),
            'DELETE' => self::format_Response(
                 response: $this->http->delete(
                     path: $path,
                    query: $query,
                )
            ),
            default => self::format_Response(
                 response: $this->http->get(
                     path: $path,
                    query: $query,
                )
            ),
        };
    }



/* FORMAT HTTP RESPONSE
----------------------------------------------------------------------------- */

    /**
     * @param ResponseInterface $response Guzzle response object.
     * @return Response Formatted response object.
     */
    private static function format_Response( ResponseInterface $response ) : Response
    {
        return new Response(
                   status: $response->getStatusCode(),
            statusMessage: $response->getReasonPhrase(),
                  headers: $response->getHeaders(),
                     body: json_decode( json: $response->getBody()->getContents())
        );
    }
}