<?php

namespace App\Services;
use Illuminate\Http\Request;
use GuzzleHttp\Client;


class FBMessenger
{
    const HUB_CHALLENGE = 'hub_challenge';
    const HUB_VERIFY_TOKEN = 'hub_verify_token';
    const MESSAGE_ID = 'entry.0.id';
    const SENDER_ID = 'entry.0.messaging.0.sender.id';
    const MESSAGE = 'entry.0.messaging.0.message.text';
    const PAYLOAD = 'entry.0.messaging.0.postback.payload';
    const BUTTONS = 'buttons';
    const TEXT = 'text';

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
        return response('Forbidden', 403);
    }
    private function createReply($type, $reply, $title = null) {
        switch($type) {
            case self::TEXT:
                return ['text' => $reply];  
                break; 
            case self::BUTTONS:
                return [
                    'attachment'=> [
                        'type' => 'template',
                        'payload' => [
                            'template_type' => 'button',
                            'text' => $title,
                            'buttons' => $reply
                        ]
                    ]
                ]; 
                break;
        }
    }
    /**
     * Send reply to facebook-user .
     *
     * @param  string $recipient This is the facebook user-id 
     * @param  array $reply This is the formatted message to send to the api 
     */
    private function sendMessage($reply, $recipient) {

            $client = new Client();
            $header = ['Accept' => 'application/json'];
            $query = [
            'access_token' => $this->config['facebook_access_token'],
            'recipient' => ['id'=> $recipient],
            'message' => $reply
            ];
            
            $response = $client->request('POST', $this->config['facebook_api_url'], [
                'headers' => $header,
                'query' => $query
            ]); 
            }             
    
    /**
    * Start conversation with user, based in what message or button the * user sends back to bot 
    * @param Request $request
    **/
    public function startConversation(Request $request) {

        $message;
        if($request->input(self::MESSAGE)){
            $message = $request->input(self::MESSAGE);
        } else if($request->input(self::PAYLOAD)) {
            $message = $request->input(self::PAYLOAD);
        }
        $recipient = $request->input(self::SENDER_ID);
        $id = $request->input(self::MESSAGE_ID);
        if($recipient !== $id) {
            switch ($message) {
                case 'first hand shake':
                    $reply = $this->createReply(self::BUTTONS, config('replies.start_buttons'), config('replies.welcome'));
                    $this->sendMessage($reply, $recipient);
                    break;
                case 'make report':
                    $reply = $this->createReply(self::BUTTONS, config('replies.start_reporting_buttons'), config('replies.start_reporting'));
                    $this->sendMessage($reply, $recipient);
                    break;
                case 'start':
                    $reply = $this->createReply(self::TEXT, config('replies.title'));
                    $this->sendMessage($reply, $recipient);
                    break;
        }
        return response('Accepted', 200);
    }
}
}
