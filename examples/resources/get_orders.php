<?php
session_start();
require_once(dirname(__DIR__, 2).'/vendor/autoload.php');

$sdk = new Hostville\Dorcas\Sdk(['credentials' => ['id' => 2, 'secret' => 'hFWx5xkPbVKXvLwD17Lbl5MFczORgKZwvawKOzpc']]);
if (empty($_SESSION['token'])) {
    $token = login_via_password($sdk, 'user@example.org', 'administrator');
    $_SESSION['token'] = $token;
}
# don't get too excited; this is a token that only works on my local machine :)
$sdk->setAuthorizationToken($_SESSION['token']);
# we set the authorization token
$orderResource = $sdk->createOrderResource();

try {
    $response = $orderResource->addQueryArgument('limit', 2)
                                ->relationships(['company', 'customers' => ['paginate' => [2, 0]]])
                                ->send('get');
    echo $response->getRawResponse();

} catch (Hostville\Dorcas\Exception\DorcasException $e) {
    dd($e->getMessage(), $e->context);
}