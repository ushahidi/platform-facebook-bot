<?php

namespace App\Services;

class FBMessenger
{
    const HUB_CHALLENGE = 'hub_challenge';
    const HUB_VERIFY_TOKEN = 'hub_verify_token';
    
    public function __construct($config) {
        $this->config = $config;

    }
    /**
    * Confirm facebook-webhook based on parameters sent in request from 
    * facebook.
    **/

    public function verifyWebhook() {
        if(isset($_GET[self::HUB_CHALLENGE]) && $_GET[self::HUB_VERIFY_TOKEN] === $this->config['facebook_verify_token'])
        {
                return $_GET[self::HUB_CHALLENGE];
        }
        return response('Bad request', 400);
    }
}