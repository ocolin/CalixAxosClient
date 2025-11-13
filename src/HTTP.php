<?php

declare( strict_types = 1 );

namespace Ocolin\Calix\Axos;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Ocolin\EasyEnv\Env;
use Psr\Http\Message\ResponseInterface;

class HTTP
{
    /**
     * @var Client Guzzle HTTP client.
     */
    public Client $client;

    /**
     * @var string Base URL of server.
     */
    public readonly string $url;

    /**
     * @var string Basic Auth username.
     */
    public readonly string $username;

    /**
     * @var string Basic Auth password.
     */
    public readonly string $password;

    /**
     * @var string[]|object|null End point URI query parameters.
     */
    public array|object|null $query = null;

    /**
     * @var string End point path.
     */
    public string $path = '';

/*
----------------------------------------------------------------------------- */

    /**
     * @param string|null $url URL of Calix AXOS Rest service.
     * @param string|null $username Authentication username.
     * @param string|null $password Authentication password.
     * @param int $timeout HTTP timeout, defaults to 20 seconds.
     * @param bool $verify Verify SSL connection, default off.
     */
    public function __construct(
        ?string $url = null,
        ?string $username = null,
        ?string $password = null,
            int $timeout = 20,
           bool $verify = false,
    )
    {
        $this->url = $url ?? Env::getString( name: 'SMX_HOST' );
        $this->username = $username ?? Env::getString( name: 'SMX_USERNAME' );
        $this->password = $password ?? Env::getString( name: 'SMX_PASSWORD' );

        $this->client = new Client([
            'base_uri' => $this->url,
            'timeout'  => $timeout,
            'connect_timeout' => $timeout,
            'verify' => $verify,
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(
                    string: "{$this->username}:{$this->password}"
                ),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
                'User-Agent' => 'Ocolin Calix AxosClient 1.0',
            ]
        ]);
    }


/*
----------------------------------------------------------------------------- */

    /**
     * @param string $path API end point path.
     * @param string[]|object|null $query Path and Query URI parameters.
     * @param string[]|object|null $body Body parameters for PUT/POST.
     * @return ResponseInterface Guzzle response object.
     * @throws GuzzleException
     */
    public function post(
        string $path,
        array|object|null $query = null,
        array|object|null $body = null,
    ) : ResponseInterface
    {
        $this->query = $query;
        $this->path  = $path;
        $this->format_Path();
        $options = [
            'query' => $this->query,
            'json'  => $body
        ];

        return $this->client->post( uri: $this->path, options: $options );
    }



/*
----------------------------------------------------------------------------- */

    /**
     * @param string $path APi end point path.
     * @param string[]|object|null $query Path and Query URI parameters.
     * @return ResponseInterface Guzzle response object.
     * @throws GuzzleException
     */
    public function get(
        string $path,
        array|object|null $query = null,
    ) : ResponseInterface
    {
        $this->query = $query;
        $this->path  = $path;
        $this->format_Path();
        $options = [ 'query' => $this->query ];

        return $this->client->get( uri: $this->path, options: $options );
    }



/*
----------------------------------------------------------------------------- */

    /**
     * @param string $path API end point path.
     * @param string[]|object|null $query Path and Query URI parameters.
     * @return ResponseInterface Guzzle response interface.
     * @throws GuzzleException
     */
    public function delete(
        string $path,
        array|object|null $query = null,
    ) : ResponseInterface
    {
        $this->query = $query;
        $this->path  = $path;
        $this->format_Path();
        $options = [ 'query' => $query ];


        return $this->client->delete( uri: $this->path, options: $options );
    }



/*
----------------------------------------------------------------------------- */

    /**
     * @param string $path End point path.
     * @param string[]|object|null $query Params for path and query URI.
     * @param string[]|object|null $body Params for PUT body.
     * @return ResponseInterface Guzzle response interface.
     * @throws GuzzleException
     */
    public function put(
        string $path,
        array|object|null $query = null,
        array|object|null $body = null,
    ) : ResponseInterface
    {
        $this->query = $query;
        $this->path  = $path;
        $this->format_Path();
        $options = [
            'query' => $this->query,
            'json'  => $body
        ];

        return $this->client->put( uri: $this->path, options: $options );
    }



/* FORMAT ENDPOINT PATH
----------------------------------------------------------------------------- */

    /**
     * If the URI path contains variables, we will replace them with the
     * variable values from the query parameter. We then remove them so they
     * are not duplicated in the query string of the URI path.
     */
    private function format_Path() : void
    {
        $this->path = $this->trim_Path( path: $this->path );
        if( empty( $this->query ) ) { return; }
        if( is_object( $this->query ) ) { $this->query = (array)$this->query; }
        if( !str_contains( haystack: $this->path, needle: '{' ) ) { return ; }

        $allowed_types = [ 'string', 'integer', 'float', 'double' ];
        foreach( $this->query as $name => $value ) {
            if(
                in_array( needle: gettype($value), haystack: $allowed_types )  AND
                str_contains( haystack: $this->path, needle: '{' . $name . '}' )
            ) {
                $this->path = str_replace(
                     search: '{' . $name . '}',
                    replace: (string)$value,
                    subject: $this->path
                );
                unset( $this->query[$name] );
            }
        }
    }



/* REMOVE DUPLICATE SLASHES IN URL
----------------------------------------------------------------------------- */

    /**
     * If both the base URL and the end point path have root slash, remove
     * the one from end point to eliminate a double slash in the final URL.
     *
     * @param string $path Original API endpoint path.
     * @return string Endpoint path without beginning / if there is one.
     */
    private function trim_Path( string $path ) : string
    {
        if(
            str_starts_with( haystack: $path, needle: '/' ) AND
            str_ends_with( haystack: $this->url, needle: '/' )
        ) {
            return trim( string: $path, characters: '/' );
        }

        return $path;
    }



    /*
    ----------------------------------------------------------------------------- */

    /**
     * @return string[] List of allowed HTTP methods.
     */
    public static function allowed_Methods(): array
    {
        return [
          'GET',
          'POST',
          'PUT',
          'DELETE',
        ];
    }
}

