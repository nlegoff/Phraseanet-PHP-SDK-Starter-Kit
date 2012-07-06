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
    $feeds = $em->getRepository('Feed')->findAll();
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
        <h1>FEEDS LIST</h1>
        <?php
        if (null !== $errMsg) {
            echo $errMsg;
        } else {
            echo '<a href="./">back</a><br/>';
            echo '<a href="./?logout">logout</a><br/>';

            foreach ($feeds as $feed) {
                /* @var $feed PhraseanetSDK\Entity\Feed */
                $output = "=========================================================<br />";
                $output .= "Title : " . $feed->getTitle();
                $output .= "<br />Subtitle : " . $feed->getSubTitle();
                $output .= "<br />Total Entries : " . $feed->getTotalEntries();
                $output .= "<br />Icon : <img src='" . $CONF['instance-url'] . $feed->getIcon() . "'/>";
                $output .= "<br /><a href='./feed-entries.php?feed=" . $feed->getId() . "&total_entries=" . $feed->getTotalEntries() . "'>feed entries</a><br />";
                echo $output;
            }
        }
        ?>
    </body>
</html>
