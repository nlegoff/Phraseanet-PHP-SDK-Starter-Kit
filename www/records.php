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
    $query = $em->getRepository('Record')->search(array(
        'query'        => 'all',
        'offset_start' => $request->get('offset', 0),
        'per_page'     => 10
        )
    );
    
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
        <h1>SEARCH FOR RECORDS</h1>
        <?php
        if (null !== $errMsg) {
            echo $errMsg;
        } else {
            echo '<a href="./">back</a><br/>';
            echo '<a href="./?logout">logout</a><br/><br /><br />';


            echo "<p>" . $query->getPerPage() . ' items displayed ' . $query->getTotalResults() . " items found in " . $query->getQueryTime() . " seconds start at item " . ($query->getOffsetStart() * $query->getPerPage()) . "</p>";

            if (($query->getOffsetStart() - 1) >= 0) {
                echo '<a href="./records.php?offset=' . ($query->getOffsetStart() - 1) . '">Previous page</a><br />';
            }

            if ((($query->getOffsetStart() + 1) * $query->getPerPage()) < $query->getTotalResults()) {
                echo '<a href="./records.php?offset=' . ($query->getOffsetStart() + 1) . '">Next page</a><br />';
            }

            foreach ($query->getResults() as $record) {
                /* @var $record PhraseanetSDK\Entity\Record */
                $output = "<div style='padding:5px;'>";

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

                $output .= "<table style='width:100%'>";
                if ($record->getCaption()->count()) {
                    $output .= "<caption><b>Caption</b></caption>";
                    foreach ($record->getCaption() as $index => $caption) {
                        /* @var $caption PhraseanetSDK\Entity\RecordCaption */
                        $output .= "<tr style='background-color:" . (($index % 2 === 0) ? "#CCC" : "#AAA" ) . ";'><td>";
                        $output .= $caption->getName() . "</td>";
                        $output .= "<td>" . $caption->getValue();
                        $output .= "</td><tr>";
                    }
                }
                $output .= "</table></div>";
                echo $output;
            }
        }
        ?>
    </body>
</html>
