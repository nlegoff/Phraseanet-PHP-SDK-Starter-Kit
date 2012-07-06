<?php

use PhraseanetSDK\Tools\Entity\Manager;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\ExceptionInterface;
use Symfony\Component\HttpFoundation\Request;

$client = require __DIR__ . '/../src/common/client.php';

/* @var $client \PhraseanetSDK\Client */
if (null === $client->getAccessToken()) {
    header(sprintf('Location: %s', $CONF['authentication-uri']));
}

$em = new Manager($client);

$errMsg = null;

try {
    $databoxCollection = $em->getRepository('DataboxCollection')->findByDatabox($request->get('databox'));
} catch (NotFoundException $e) {
    $errMsg = $e->getMessage();
} catch (ExceptionInterface $e) {
    $errMsg = $e->getMessage();
}
?>
<html>
    <head>

    </head>
    <body>
        <h1>COLLECTIONs LIST FOR DATABOX WITH ID <?php echo $request->get('databox');?></h1>
        <?php
        if (null !== $errMsg) {
            echo $errMsg;
        } else {
            echo '<a href="./databoxes.php">back</a><br/>';
            echo '<a href="./?logout">logout</a><br/>';

            foreach ($databoxCollection as $collection) {
                /* @var $collection PhraseanetSDK\Entity\DataboxCollection */
                $output = "=========================================================<br />";
                $output.= 'Name : ' . $collection->getName() . '<br />';
                $output.= 'Record Amount : ' . $collection->getRecordAmount() . '<br />';

                echo $output;
            }
        }
        ?>
    </body>
</html>
