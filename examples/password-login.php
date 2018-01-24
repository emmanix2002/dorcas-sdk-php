<?php
session_start();
require_once(dirname(__DIR__).'/vendor/autoload.php');

$sdk = new Hostville\Dorcas\Sdk(['credentials' => ['id' => 2, 'secret' => 'hFWx5xkPbVKXvLwD17Lbl5MFczORgKZwvawKOzpc']]);
try {
    if (empty($_SESSION['token'])) {
        $response = login_via_password($sdk, 'fake-admin@yemisi.com', 'randomPass');
        if (is_string($response)) {
            $_SESSION['token'] = $response;
        } else {
            dd($response->getRawResponse());
        }
    }
    dd($_SESSION['token']);
} catch (Hostville\Dorcas\Exception\DorcasException $e) {
    dd($e->getMessage(), $e->context);
} catch (Exception $e) {
    dd($e->getMessage());
}