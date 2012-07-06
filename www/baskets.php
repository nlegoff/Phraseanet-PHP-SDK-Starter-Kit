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
    $baskets = $em->getRepository('Basket')->findAll();
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
        <h1>BASKETS LIST</h1>
        <?php
        if(null !== $errMsg) {
            echo $errMsg;
        } else {
            echo '<a href="./">back</a><br/>';
            echo '<a href="./?logout">logout</a><br/>';

            foreach ($baskets as $basket) {
                /* @var $basket PhraseanetSDK\Entity\Basket */
                $output = "=========================================================<br />";
                $output .= "Name : " . $basket->getName();
                $output .= "<br />Description : " . $basket->getDescription();
                $output .= "<br />createdOn : " . $basket->getCreatedOn()->format("d/m/Y H:i:s");
                $output .= "<br /><a href='./basket-elements.php?basket=" . $basket->getBasketId() . "'>basket elements</a><br />";
                echo $output;
            }
        }
        ?>
    </body>
</html>
