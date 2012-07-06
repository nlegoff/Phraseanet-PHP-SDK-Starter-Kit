<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use StarterKit\MyClient as Client;
use Guzzle\Http\Client as HttpClient;
use Symfony\Component\HttpFoundation\Request;
//use Doctrine\Common\Cache\ArrayCache;
//use Guzzle\Common\Cache\DoctrineCacheAdapter;
//use Guzzle\Http\Plugin\CachePlugin;
//$backend = new ArrayCache();
//$adapter = new DoctrineCacheAdapter($backend);
//$cache = new CachePlugin($adapter);


$CONF = require __DIR__ . "/../../conf/configuration.php";


$request = Request::createFromGlobals();

foreach ($CONF as $value) {
    if ('' === $value) {
        header(sprintf('Location: %s', 'configuration.php'));
    }
}

$httpClient = new HttpClient();
$httpClient->setBaseUrl($CONF['instance-url']);

//$httpClient->addSubscriber($cache);

$client = new Client($CONF['api-key'], $CONF['api-secret'], $httpClient);

return $client;