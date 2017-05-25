<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FBMessenger;
class BotController extends Controller
{
    public function __construct(FBMessenger $messenger) {
        $this->messenger = $messenger;
    }

    public function initialise() {
       return $this->messenger->verifyWebhook();
    }

    public function receiveMessage(Request $request){
        $this->messenger->startConversation($request);
    }

}