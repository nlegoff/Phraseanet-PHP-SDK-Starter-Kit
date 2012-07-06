<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<html>

    <head>

    </head>

    <body>
        <h1>CONFIGURATION</h1>

        <p>There is a missing value in your configuration file located here <b><?php echo realpath(__DIR__ . '/../conf/configuration.php') ?></b></p>

        <h2>Your Current configuration</h2>
        <div>
            <?php
                $required = array('dev-token', 'instance-url', 'api-key', 'api-secret', 'callback-uri', 'authentication-uri');

                $CONF = require __DIR__ . '/../conf/configuration.php';
                foreach ($required as $confkey) {
                    if( ! array_key_exists($confkey, $CONF)) {
                        echo "<div style='color:red'> Missing ".$confkey." configuration key</div>";
                    }
                }

                foreach($CONF as $key => $value) {
                    echo "<div><span style='width:120px;display:inline-block'>".$key."</span>  <span style='display:inline-block'>".('' === $value ? '<span style="color:red"><i><b>MUST not be empty</b></i></span>' : $value)."</span></div>";
                }
            ?>
        </div>
        <hr />
        <h3>Meaning of onfiguration keys</h3>
        <div>
            <div>
                <b>dev-token</b> dev- token is a valid access token that you can use to authenticate yourself on your application.
            </div>
            <div>
                <b>api-key & api-secret</b> api-key & api-secret corresponds to your client id & client secret <br/> it's like a login and a password that allows your application to be authenticated & recognised by Phraseanet.
            </div>
            <div>
                 <b>instance-uri</b> instance-ur is the URL of the phraseanet instance where your application is declared
            </div>
            <div>
                <b>callback-uri</b> callback-uri is the URL where the user will be redirected when he accepts to give access to his personnal informations to your application.
            </div>
            <div>
               <b>authentication-uri</b> authentication-uri is the URL where the user can click on the authorization URL.
            </div>
            <div>
                <i>* callback-uri & authentication-uri can be the same like the starter kit which is the index page</i>
            </div>
        </div>
        <hr />
        <h3>How to get all this values and set the configuration properly ?</h3>
        <div>
            <p>
                <div>First of all, to use the phraseanet API, you MUST create an application on the Phraseanet instance you want reach, under YOUR_INSTANCE_URI/login/account.php</div>
                <img src="./img/step1.png" style='margin:5px;'/>
            </p>

            <p>
                <div>
                    Then fill the form to create an application
                    <div>
                        * <i>The first url is an infomative url. Before an user accept to give access to his information he can follow this link and learn more about you or your application.</i>
                    </div>
                </div>
                 <img src="./img/step2.png" style='margin:5px;'/>
            </p>

            <p>
                <div>
                    Well now you got your application. With some usefull informations like the client id, the client secret and the dev token
                </div>
                <img src="./img/step3.png" style='margin:5px;'/>
            </p>
        </div>

    </body>
</html>
