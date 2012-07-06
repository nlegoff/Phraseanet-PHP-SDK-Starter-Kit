<?php

namespace StarterKit;

use PhraseanetSDK\Client;

class MyClient extends Client
{
    protected $session;

    private function initSession()
    {
        if ( ! session_id()) {
            session_start();
        }

        $sessionKey = sprintf('oauth_', $this->apiKey);

        $this->session = &$_SESSION[$sessionKey];
    }

    public function getAccessToken()
    {
        $this->initSession();

        $this->accessToken = $this->session['token'];

        return parent::getAccessToken();
    }

    public function setAccessToken($token)
    {
        $this->initSession();

        $this->session['token'] = $token;

        return parent::setAccessToken($token);
    }
}
