<?php

require_once(dirname(__DIR__).'/vendor/autoload.php');

$sdk = new Hostville\Dorcas\Sdk(['credentials' => ['id' => 2, 'secret' => 'hFWx5xkPbVKXvLwD17Lbl5MFczORgKZwvawKOzpc']]);
$registrationService = $sdk->createRegistrationService();
# create the service
try {
    $response = $registrationService->addBodyParam('email', 'fake-admin@yemisi.com')
                                    ->addBodyParam('password', 'randomPass')
                                    ->addBodyParam('firstname', 'Yemisi')
                                    ->addBodyParam('lastname', 'Solomon')
                                    ->addBodyParam('phone', '08131886789')
                                    ->addBodyParam('company', 'Book Readers Club')
                                    ->send('post');
    dd($response->getRawResponse());
} catch (Hostville\Dorcas\Exception\DorcasException $e) {
    dd($e->getMessage(), $e->context);
}