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

$offsetStart = $request->get('offset_start', 0);
$perPage = $request->get('per_page', 5);
$totalEntries = $request->get('total_entries');

$errMsg = null;

try {
    $entries = $em->getRepository('Entry')->findByFeed($request->get('feed'), $offsetStart, $perPage);
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
        <h1>FEED ENTRIES FOR FEED WITH ID <?php echo $request->get('feed'); ?></h1>
        <?php
        if (null !== $errMsg) {
            echo $errMsg;
        } else {
            echo '<a href="./databoxes.php">back</a><br/>';
            echo '<a href="./?logout">logout</a><br/>';

            if (null !== $totalEntries) {
                if (($offsetStart - 1) >= 0) {
                    echo '<a href="./feed-entries.php?offset=' . ($offsetStart - 1) . '&total_entries=' . $totalEntries . '&feed=' . $request->get('feed') . '">Previous page</a><br />';
                }

                if ((($offsetStart + 1) * $perPage) < $totalEntries) {
                    echo '<a href="./feed-entries.php?offset=' . ($offsetStart + 1) . '&total_entries=' . $totalEntries . '&feed=' . $request->get('feed') . '">Next page</a><br />';
                }
            }
            foreach ($entries as $entry) {
                /* @var $entry PhraseanetSDK\Entity\FeedEntry */
                $output = "=========================================================<br />";
                $output.= 'Author Email : ' . $entry->getAuthorEmail() . '<br />';
                $output.= 'Author Name : ' . $entry->getAuthorName() . '<br />';
                $output.= 'Title : ' . $entry->getTitle() . '<br />';
                $output.= 'Subtitle : ' . $entry->getSubtitle() . '<br />';


                if ($entry->getItems()->count() > 0) {
                    $output.= 'Entry Items <br/><br/>';
                    foreach ($entry->getItems() as $item) {
                        /* @var $item PhraseanetSDK\Entity\FeedEntryItem */
                        $record = $item->getRecord();
                        $output .= "<h2>" . $record->getTitle() . "</h2>";

                        $thumb = $record->getThumbnail();

                        if (null !== $thumb && null !== $permalink = $thumb->getPermalink()) {
                            $output .= '<img src = "' . $permalink->getUrl() . '" /><br />';
                        }

                        $output .= "<table style='width:100%'>";
                        $output .= "<caption><b>Informations</b></caption>";
                        $output .= "<tr style='background-color:#CCC;'><td>OriginalName</td><td>" . $record->getOriginalName() . "</td></tr>";
                        $output .= "<tr style='background-color:#AAA;'><td>Created at</td><td>" . $record->getCreatedOn()->format('d/m/Y H:i:s') . "</td></tr>";
                        $output .= "<tr style='background-color:#CCC;'><td>Mime Type</td><td>" . $record->getMimeType() . "</td></tr>";
                        $output .= "</table>";
                    }
                }

                echo $output;
            }
        }
        ?>
    </body>
</html>
