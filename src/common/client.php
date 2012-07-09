<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use StarterKit\MyClient as Client;
use Guzzle\Http\Client as HttpClient;
use PhraseanetSDK\HttpAdapter\Guzzle as HttpAdapter;
use Symfony\Component\HttpFoundation\Request;


$CONF = require __DIR__ . "/../../conf/configuration.php";


$request = Request::createFromGlobals();

foreach ($CONF as $value) {
    if ('' === $value) {
        header(sprintf('Location: %s', 'configuration.php'));
    }
}

$httpClient = new HttpClient();
$httpClient->setBaseUrl($CONF['instance-url']);

$HttpAdapter = new HttpAdapter($httpClient);

$client = new Client($CONF['api-key'], $CONF['api-secret'], $HttpAdapter);

return $client;