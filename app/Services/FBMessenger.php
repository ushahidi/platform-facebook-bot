<?php

namespace App\Services;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Report;
use App\Models\Answer;
use GuzzleHttp\Exception\ClientException;

class FBMessenger
{
    const HUB_CHALLENGE = 'hub_challenge';
    const HUB_VERIFY_TOKEN = 'hub_verify_token';
    const MESSAGE_ID = 'entry.0.id';
    const SENDER_ID = 'entry.0.messaging.0.sender.id';
    const MESSAGE = 'entry.0.messaging.0.message.text';
    const PAYLOAD = 'entry.0.messaging.0.postback.payload';
    const LOCATION = 'entry.0.messaging.0.message.attachments.0.payload.coordinates';
    const URL = 'entry.0.messaging.0.message.attachments.0.payload.url';

    public function __construct($config) {
        $this->config = $config;
        $this->platform = new PlatformApiService($this->config['ushahidi']);
    }

    /**
    * Confirm facebook-webhook based on parameters sent in request from 
    * facebook.
    **/
    public function verifyWebhook()
    {
        if(isset($_GET[self::HUB_CHALLENGE]) && $_GET[self::HUB_VERIFY_TOKEN] === $this->config['facebook']['facebook_verify_token']) {
            return $_GET[self::HUB_CHALLENGE];
        }
        return response('Forbidden', 403);
    }

    /**
    * Start conversation with user, based on what message or button the 
    * user sends back to bot 
    **/
    public function startConversation(Request $request) 
    {
        // the user_id we will reply to
        $recipient = $request->input(self::SENDER_ID);

        // the message id
        $messageId = $request->input(self::MESSAGE_ID);
        // if the user have an ongoing report, we fetch it from database.
        $ongoingReport = Report::where('user_id', $recipient)->first();
        /* reading the message from FB */
        
        // When the user send a written-message
        if($request->input(self::MESSAGE)) {
            $message = $request->input(self::MESSAGE);
            $message_type = 'text';
        }
        // When the user selects a button
        else if($request->input(self::PAYLOAD)) {
            $message = $request->input(self::PAYLOAD);
            $message_type = 'payload';
        }
        // When the user adds media
        else if($request->input(self::URL)){
            $message = $request->input(self::URL);
            $message_type = 'url';
        } 
        // When the user shares location
        else if($request->input(self::LOCATION)) {
            $message_type = 'location received';
            $message = $request->input(self::LOCATION);
        }
        // When the user makes a response we have not configured
        else if($ongoingReport['last_question'] === 'image location') {
            $message_type = 'location received';
            $message = 'no location';
        } else {
            $message_type = 'text';
            $message = 'not readable';
        }
        /* starting the conversation */                
        if($recipient !== $messageId) {
            if($message_type === 'location received') {
                $command = $message_type;
            } else {
                $command = strtolower($message);
            }

            // getting the bot-answer from database 
            $answers = Answer::where('command', '=', $command)->first();
            if(!empty($answers) && $message_type !== 'location received') {
                $reply = unserialize($answers->answers);
                // delete $ongoingReport if user do something that is not connected to the reporting-flow
                if(!$answers->reporting_flow) {
                    if($ongoingReport) {
                        $ongoingReport->delete();
                    }
                }
                // initialise report if user wants to start reporting
                if($command === 'make report') {
                    $this->initialiseReport($recipient);}
                // send a reply to the user
                $this->sendMessage($reply, $recipient);
            }  else if($ongoingReport) {
                // go to the reporting flow if there is an ongoing report
                $this->reportingFlow($ongoingReport, $message, $recipient, $message_type);
            } else if($message_type === 'url') {
                // whenever the user sends an image outside the reportingflow

                $this->initialiseReport($recipient, null, $message);
                $reply = $this->formatForms();
                $this->sendMessage($reply, $recipient);
            } else {
                // if the user writes something that does not makes sense
                if($ongoingReport) {
                    $ongoingReport->delete();
                }
                $answers = Answer::where('command', '=', 'not readable')->get()->random();
                $this->sendMessage(unserialize($answers->answers), $recipient);
            }
        }
        return response('Accepted', 200);
    }
    /**
    * Fetch attributes from platform and initialise a report in report-database 
    * $param string $recipient This is the facebook user-id
    * $param string $image Image-url sent by user
    */
    private function initialiseReport($recipient, $image = null) {
        $formId = $this->config['ushahidi']['platform_form_id'];
            $attributes = $this->platform->getAttributes($formId);
        Report::create(['user_id' => $recipient, 'attributes'=> json_encode($attributes), 'replies'=> json_encode(['content' => ' ', 'image'=> $image]), 'last_question' => 'make report']);
    }

    /**
     * Send reply to facebook-user .
     * @param  string $recipient This is the facebook user-id 
     * @param  array $answer This is the formatted messages from Answers-table, to send to * fb-api 
     */
    private function sendMessage($answers, $recipient)
    {
        foreach($answers as $answer){
            $client = new Client();
            $header = ['Accept' => 'application/json'];
            $query = [
                'access_token' => $this->config['facebook']['facebook_access_token'],
                'recipient' => ['id' => $recipient],
                'message' => $answer
            ];

            // todo: Catch error-responses
            try {
            $client->request('POST', $this->config['facebook']['facebook_api_url'], [
                'headers' => $header,
                'query' => $query
            ]);
            } catch (ClientException $e) {
                \Log::ERROR('error when sending message to facebook: ' . $e->getMessage());
            }   
        }
    }
           
    /* Start collecting reports */
    private function reportingFlow($ongoingReport, $message, $recipient, $message_type)
    {
        $replies = json_decode($ongoingReport->replies);
        if($message === 'send' || $message === 'Send my report') {
            // saving the report to the platform
            $nextQuestion = $this->platform->savePost($ongoingReport);
            if($nextQuestion !== 'platform error') {
            // deletes the ongoing report
                $ongoingReport->delete();
            }
        } else if($ongoingReport->last_question === 'make report') {
            // saves the reporttext
            $replies->content = $message;
            $ongoingReport->replies = json_encode($replies);
            $ongoingReport->last_question = 'image location';
            if(!isset($replies->image)){
                $nextQuestion = 'image location' ;
            } else {
                $nextQuestion = 'image received';
            }
            $ongoingReport->save();
        } else if($ongoingReport->last_question === 'image location'){
            if($message_type === 'url') {
                // saving image
                $replies->image = $message;
                $ongoingReport->replies = json_encode($replies);
                $ongoingReport->save();
                if(!isset($replies->location)) {
                    // when the user has shared an image but there is no location saved
                    $nextQuestion = 'image received';    
                } else {
                    // if user has saved location and image, we ask if they want to save report to platform
                    $nextQuestion = 'send report';
                }
            } else if($message_type === 'location received') {
                // saves the location

                if(isset($message['lat'])) {
                    $replies->location = ['lat' => $message['lat'], 'lon' => $message['long']];
                } else {
                    $replies->last_question = 'location';
                }
                $ongoingReport->replies = json_encode($replies);
                $ongoingReport->save();
                if(isset($message['lat']) && !isset($replies->image)) {
                    // when the user has shared location but there is no image saved
                    $nextQuestion = 'location received';
                } else if (!isset($message['lat'])) {
                    $nextQuestion = 'no location';
                } else {
                    // if the user has saved location and image, we ask if they want to save report to platform
                    $nextQuestion = 'send report';
                }
            } else if($message_type === 'text') {
                // if the user sends multiple texts, we add them to the report-text
                $replies->content = $replies->content . ' ' . $message;
                $ongoingReport->replies = json_encode($replies);
                $ongoingReport->save();
                if(isset($replies->last_question) && $replies->last_question === 'location') {
                    $nextQuestion = 'location received';
                } else { 
                    $nextQuestion = 'image location';
                }
            }else if($message_type='payload') {
                // saving report to platform
                $nextQuestion = $this->platform->savePost($ongoingReport);
                $ongoingReport->delete();
            }
        } 
         else {
            // if the user does something that does not makes sense
            $nextQuestion = 'not readable';
        } 
        $answers = Answer::where('command', '=', $nextQuestion)->first();
        $this->sendMessage(unserialize($answers->answers), $recipient);
        return response('Accepted', 200);
    }
}
