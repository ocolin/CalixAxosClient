# Calix Axos Client

A small PHP REST Client for Calix AXOS.

## Instantiation

First we must instantiate a client and provide it with a URL for the server, as well as a username and password to authenticate with the server. These can also be provided by environment variables instead of constructor arguments.

### Constructor arguments

* url - The URL and port to the AXOS server.
* username - Username of account making the API calls.
* password - Password of account making API calls.
* timeout - HTTP timeout in seconds. Defaults to 20 seconds.
* verify - Verify SSL connection. Defaults to off.

### Environment variables

* SMX_HOST - URL of AXOS REST server, including port.
* SMX_USERNAME - Username on server to login as.
* SMX_PASSWORD - Password of user to login as.

### Example with environment variables

```php
$_ENV['SMX_HOST'] = 'https://server.com:18443/rest/v1/';
$_ENV['SMX_USERNAME'] = 'myuser';
$_ENV['SMX_PASSWORD'] = '123456'

$client = new Ocolin\Calix\Axos\Client();
```

### Example - Environment with optional parameters
```php
$_ENV['SMX_HOST'] = 'https://server.com:18443/rest/v1/';
$_ENV['SMX_USERNAME'] = 'myuser';
$_ENV['SMX_PASSWORD'] = '123456'

$client = new Ocolin\Calix\Axos\Client(
    timeout: 100,
     verify: true,
);
```

### Example - Using constructor arguments
```php
$client = new Ocolin\Calix\Axos\Client(
    url: 'https://server.com:18443/rest/v1/',
    username: 'myuser',
    password: '123456'
);
```

## Making API calls

### Full

This function will return an object containing:

* HTTP status code
* HTTP status message
* HTTP headers
* HTTP response body

### Call

This is the same as full, but will return only the response body.

### Example GET

```php
$output = $client->call(
    path: '/ems/subscriber/org/{org-id}/account/{account-name}',
    query: [
        'orig-id' => 'Calix',
        'account-name' => 123
    ]
);
```

### Example POST (Create)
```php
$output = $client->call(
    path: '/ems/subscriber',
    method: 'POST',
    body: [
        'name' => 'PHPUnit test',
                'customId' => 777,
                'type' => 'Residential',
                'orgId' => 'Calix',
                'locations' => [ ... ]
    ]
);
```

### Example PUT (Update)

```php
$output = $client->call(
    path: '/ems/subscriber/org/{org-id}/account/{account-name}',
    method: 'PUT',
    query: [
        'orig-id' => 'Calix',
        'account-name' => 123
    ]
    body: [
        'name' => 'New name',
        'orgId' => 'Calix',
        'customId' => 123,
    ]
);
```

### Example DELETE

```php
$output = $client->call(
    path: '/ems/subscriber/org/{org-id}/account/{account-name}',
    method: 'DELETE',
    query: [
        'orig-id' => 'Calix',
        'account-name' => 123
    ]
);
```