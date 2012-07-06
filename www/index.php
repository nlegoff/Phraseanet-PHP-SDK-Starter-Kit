<?php
use PhraseanetSDK\Exception\AuthenticationException;
use PhraseanetSDK\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use StarterKit\MyClient as Client;

$client = require __DIR__ . '/../src/common/client.php';

$request = Request::createFromGlobals();

$client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION, array('redirect_uri' => $CONF['callback-uri']), $request);

$authErrorMsg = null;

if(null !== $request->get('logout')) {
    $client->setAccessToken(null);
}

try {
    $client->retrieveAccessToken($request);
} catch (AuthenticationException $e) {
    $authErrorMsg = $e->getMessage();
} catch (RuntimeException $e) {
    $authErrorMsg = $e->getMessage();
}

?>

<html>
    <head></head>
    <body>
        <H1>STARTER KIT  & EXAMPLES</H1>
        <?php
        if (null === $client->getAccessToken()) {
            echo '<a href="' . $client->getAuthorizationUrl() . '">You can login</a>';
            if(null !== $authErrorMsg) {
                 echo 'An error occured during authentication '. $authErrorMsg;
            }
        } else {
           ?>
            <div>
                You are authenticated <br/>
                <a href="./?logout">You can logout</a> or play with the API.
            </div>
            <ul>
                <li><a href='./records.php'>Records</a></li>
                <li><a href='./baskets.php'>Baskets</a></li>
                <li><a href='./databoxes.php'>Databoxes</a></li>
                <li> <a href='./feeds.php'>Feeds</a></li>
            </ul>
        <?php
        }
        ?>
    </body>
</html>