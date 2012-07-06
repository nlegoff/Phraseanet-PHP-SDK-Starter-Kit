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
    $basketElements = $em->getRepository('BasketElement')->findByBasket($request->get('basket'));
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
        <h1>ELEMENT LIST FOR BASKET WITH ID <?php echo $request->get('basket');?></h1>
        <?php
        if (null !== $errMsg) {
            echo $errMsg;
        } else {
            echo '<a href="./baskets.php">back</a><br/>';
            echo '<a href="./?logout">logout</a><br/>';

            foreach ($basketElements as $element) {
                /* @var $element PhraseanetSDK\Entity\BasketElement */
                $output = "=========================================================<br />";
                $record = $element->getRecord();
                $thumb = $record->getThumbnail();

                if (null !== $thumb && null !== $permalink = $thumb->getPermalink()) {
                    $output .= '<img src = "' . $permalink->getUrl() . '" /><br />';
                }

                $output .= "Title : " . $record->getTitle() . "";
                $output .= "OriginalName : " . $record->getOriginalName() . "<br />";
                $output .= "Created at : " . $record->getCreatedOn()->format('d/m/Y H:i:s') . "<br />";
                $output .= "Mime Type : " . $record->getMimeType() . "<br />";

                if ($element->isValidationItem()) {

                    $output .= '<br />Validation Participants :<br />';
                    foreach ($element->getValidationChoices() as $choice) {
                        /* @var $choice PhraseanetSDK\Entity\BasketValidationChoice */
                        $participant = $choice->getValidationUser();
                        $output .= '<br /> - ' . $participant->getUsrName() . '<br />';
                        switch ($choice->getAgreement()) {
                            case true:
                                $output .= "Aggrement : OK<br />";
                                break;
                            case false:
                                $output .= "Aggrement : KO<br />";
                                break;
                            default:
                                $output .= "Aggrement : Unknown<br />";
                        }
                    }
                } else {
                    $output .= "Validation item : NO<br />";
                }

                echo $output;
            }
        }
        ?>
    </body>
</html>
