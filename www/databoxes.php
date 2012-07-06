<?php

use PhraseanetSDK\Tools\Entity\Manager;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\ExceptionInterface;

$client = require __DIR__ . '/../src/common/client.php';

/* @var $client \PhraseanetSDK\Client */
if (null === $client->getAccessToken()) {
    header(sprintf('Location: %s', $CONF['authentication-uri']));
}

$em = new Manager($client);

$errMsg = null;

try {
    $databoxes = $em->getRepository('Databox')->findAll();
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
        <h1>DATABOXES LIST</h1>
        <?php
        if(null !== $errMsg) {
            echo $errMsg;
        } else {
            echo '<a href="./">back</a><br/>';
            echo '<a href="./?logout">logout</a><br/>';

            foreach ($databoxes as $databox) {
                /* @var $databox PhraseanetSDK\Entity\Databox */
                $output = "=========================================================<br />";
                $output .= "Name : " . $databox->getName();
                $output .= "<br />Version : " . $databox->getVersion();
                $output .= "<br /><a href='./databox-collections.php?databox=" . $databox->getDataboxId() . "'>databox collection</a><br />";
                echo $output;
            }
        }
        ?>
    </body>
</html>
