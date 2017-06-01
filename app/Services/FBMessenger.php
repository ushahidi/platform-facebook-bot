<?php

namespace App\Services;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Report;
use App\Models\Answer;

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
        $this->platform = new PlatformApiService(config('options.ushahidi'));
    }

    /**
    * Confirm facebook-webhook based on parameters sent in request from 
    * facebook.
    **/
    public function verifyWebhook()
    {
        if(isset($_GET[self::HUB_CHALLENGE]) && $_GET[self::HUB_VERIFY_TOKEN] === $this->config['facebook_verify_token']) {
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
        $location = null;
        // When the user send a written-message
        if($request->input(self::MESSAGE)) {
            $message = $request->input(self::MESSAGE);
        }
        // When the user selects a button
        else if($request->input(self::PAYLOAD)) {
            $message = $request->input(self::PAYLOAD);
        }
        // When the user adds media
        else if($request->input(self::URL)){
            $message = $request->input(self::URL);
        } 
        // When the user shares location
        else if($request->input(self::LOCATION)) {
            $message = 'location';
            $location = $request->input(self::LOCATION);
        }
        // When the user makes a response we have not configured
        else {
            $message = 'not readable';
        }
        // the user_id we will reply to
        $recipient = $request->input(self::SENDER_ID);
        // the message id
        $messageId = $request->input(self::MESSAGE_ID);
        // if the user have an ongoing report.
        $ongoingReport = Report::where('user_id', $recipient)
            ->first();

        if($recipient !== $messageId) {
            $message = strtolower($message);
            $answer = Answer::where('command', '=', $message)->first();
            if($answer) {
                $answer = json_decode($answer->answer);
                // always deleting an ongoing report if user choose to click on an old button/give another command than answering the question, that indicates user wants out of the report...
                if($ongoingReport) {
                    $ongoingReport->delete();
                }
                if($message === 'start') {
                // creates a start-report
                    Report::create(['user_id' => $recipient, 'last_question' => 'title']);
                }
                // sending reply to user
                $this->sendMessage($answer, $recipient);    
            } else if($ongoingReport) {
                // starts the reporting-flow
                $this->startReporting($ongoingReport, $message, $recipient, $location);
            } else {
                // if the user writes something that does not makes sense
                if($ongoingReport) {
                    $ongoingReport->delete();
                }
                $answer = Answer::where('command', '=', 'not readable')->first();
                $this->sendMessage(json_decode($answer->answer), $recipient);
            }
        }
        return response('Accepted', 200);

    }

    /**
     * Send reply to facebook-user .
     * @param  string $recipient This is the facebook user-id 
     * @param  array $reply This is the formatted message from Answers-table, to send to * fb-api 
     */
    private function sendMessage($reply, $recipient)
    {
        $client = new Client();
        $header = ['Accept' => 'application/json'];
        $query = [
            'access_token' => $this -> config['facebook_access_token'],
            'recipient' => ['id' => $recipient],
            'message' => $reply
        ];

        // todo: Catch error-responses
        $response = $client -> request('POST', $this->config['facebook_api_url'], [
            'headers' => $header,
            'query' => $query
        ]);
    }
            
    /* Start collecting reports */
    private function startReporting($ongoingReport, $message, $recipient, $location)
    {
       // todo: figure out something more clean for below code and fetch attributes from platform-api instead of hardcoding them... 
        switch ($ongoingReport->last_question) {
            case 'title':
                $ongoingReport->replies = json_encode([
                    'title' => $message
                ]);
                $command = 'description';
                $ongoingReport->last_question = $command;
                $ongoingReport->save();
                break;
            case 'description':
                $replies = json_decode($ongoingReport->replies);
                $replies->content = $message;
                $ongoingReport->replies = json_encode($replies);
                $command = 'image';
                $ongoingReport->last_question = $command;
                $ongoingReport->save();
                break;
            case 'image':
                //check if image-url, else send another question.
                $replies = json_decode($ongoingReport->replies);
                $replies->image = $message;
                $ongoingReport->replies = json_encode($replies);
                $command = 'find_location';
                $ongoingReport->last_question = 'location';
                $ongoingReport->save();
                break;
            case 'location':
                if(isset($location)) {
                //extracting lat/long if user shared location
                $replies = json_decode($ongoingReport->replies);
                $replies->location = [
                    'lat'=>$location['lat'],
                    'lon'=>$location['long']
                    ];
                $ongoingReport->replies = json_encode($replies);
                }
                $command = 'finished';
                $ongoingReport->last_question = $command;
                $ongoingReport->save();
                break;
            case 'finished':
            // if the user clicks exit, bot will delete the ongoing report and start conversation-loop from the beginning again
                 if($message === 'send') {
                      $command = $this->platform->savePost($ongoingReport->replies);
                } else {
                    $command = 'not readable';
                }
                break;
            default:
            // if the user do something that the bot does not understand
            $command = 'not readable';
            break;
        }
        // getting the answer from db based on value of $command
        $answer = Answer::where('command', '=', $command)->first();
        $answer = json_decode($answer->answer);
        $this->sendMessage($answer, $recipient);
    }
}
