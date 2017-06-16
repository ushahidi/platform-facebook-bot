<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Services\FBMessenger;

class BotController extends BaseController
{
    public function __construct(FBMessenger $messenger) {
        $this->messenger = $messenger;
    }

    public function initialise() {
       return $this->messenger->verifyWebhook();
    }

    public function receiveMessage(Request $request){
        $signature = $request->header('X-Hub-Signature');
        $postdata = file_get_contents("php://input");
        // checking if request comes from Fb
        if(hash_hmac('sha1', $postdata, config('options.facebook.facebook_secret')) === substr($signature, 5)) {
            //sending request to fb-service
            $this->messenger->startConversation($request);    
        } else {
            // request is not from fb, sending a 403
            return response('Forbidden', 403);
        }
    }
}