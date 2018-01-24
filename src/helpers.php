<?php

/**
 * Checks if this library was installed via composer.
 *
 * @return bool
 */
function is_installed_via_composer(): bool
{
    $assumedVendorDir = dirname(__DIR__, 3);
    # try to see if we can find a vendor directory
    $isVendorDir = ends_with($assumedVendorDir, 'vendor');
    # check if it's actually a vendor directory
    $hasAutoloadFile = file_exists(implode(DIRECTORY_SEPARATOR, [$assumedVendorDir, 'autoload.php']));
    # check if there's an autoload.php file present inside the vendor directory
    return $isVendorDir && $hasAutoloadFile;
}

/**
 * Returns a path string relative to the application base directory.
 *
 * @param string|null $path
 *
 * @return string
 */
function app_path(string $path = null): string
{
    $level = is_installed_via_composer() ? 4 : 1;
    # the level in the tree to reach the application root path
    $appDir = dirname(__DIR__, $level);
    return implode(DIRECTORY_SEPARATOR, [$appDir, (string) $path]);
}

/**
 * Returns the HTTP Client to use for making the requests.
 *
 * @param \GuzzleHttp\Psr7\Uri|null $uri
 *
 * @return \GuzzleHttp\Client
 */
function http_client(\GuzzleHttp\Psr7\Uri $uri = null): \GuzzleHttp\Client
{
    $options = [
        \GuzzleHttp\RequestOptions::ALLOW_REDIRECTS => true,
        \GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => 30.0,
        \GuzzleHttp\RequestOptions::TIMEOUT => 30.0,
        \GuzzleHttp\RequestOptions::HEADERS => [
            'User-Agent' => 'dorcas-sdk-php/'.Hostville\Dorcas\Sdk::VERSION
        ]
    ];
    if (!empty($baseUrl)) {
        $options['base_uri'] = $uri->getScheme() . '://' . $uri->getAuthority();
        $options['base_uri'] .= !empty($uri->getPath()) ? '/'.$uri->getPath() : '';

    }
    # the client options
    return new \GuzzleHttp\Client($options);
}

/**
 * Loads the manifest.json file into an array.
 *
 * @return array
 */
function load_manifest(): array
{
    $contents = file_get_contents(app_path('manifest.json'));
    # read the manifest.json file in
    return json_decode($contents, true) ?? [];
}

/**
 * A small utility function to wrap the PHP parse_str function.
 *
 * @param string $queryString
 *
 * @return array
 */
function parse_query_parameters(string $queryString): array
{
    $params = [];
    parse_str($queryString, $params);
    return $params;
}

/**
 * Performs a login for using the provided details; if successful, it returns the "access_token", else it will
 * return the actual response object.
 *
 * NOTE: The client_id, and client_secret must correspond to a Password Grant Client issued to you.
 *
 *
 * @param Hostville\Dorcas\Sdk $sdk
 * @param string      $username
 * @param string      $password
 *
 * @return Hostville\Dorcas\DorcasResponse|string
 * @throws Hostville\Dorcas\Exception\DorcasException
 */
function login_via_password(Hostville\Dorcas\Sdk $sdk, string $username, string $password)
{
    $service = $sdk->createPasswordLoginService();
    $response = $service->addBodyParam('username', $username)
                        ->addBodyParam('password', $password)
                        ->send('post');
    # sends a HTTP POST request with the parameters
    return $response->isSuccessful() ? $response->getData()['access_token'] : $response;
}