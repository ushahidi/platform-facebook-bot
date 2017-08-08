<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStartMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $startMessage = serialize([['text' => "Hello! Welcome to Uchaguzi Citizen Reporter. To send a report to Uchaguzi, please click on 'Send a report' below and follow the instructions."], ['text' => "Uchaguzi's aim is to help Kenya have a free, fair, peaceful, and credible general election."], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Keep an eye on the vote and submit any reports of issues that impact the election', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]]);

            DB::table('answers')
            ->where('command', 'first hand shake')
            ->update(['answers'=> $startMessage]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $startMessage = serialize([['text' => "Hello! Welcome to Uchaguzi Citizen Reporter."], ['text' => "Uchaguzi's aim is to help Kenya have a free, fair, peaceful, and credible general election."], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Keep an eye on the vote and submit any reports of issues that impact the election', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]]);

            DB::table('answers')
            ->where('command', 'first hand shake')
            ->update(['answers'=> $startMessage]);   
    }
}
