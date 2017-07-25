<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddErrorMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $errorMessages = [
            ['command' => 'not readable', 'language' => 'en',
                'answers' =>serialize([['text' =>"Sorry, I'm just a bot built for reporting, so my vocabulary is pretty limited."], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'What do you want to do next?', 'buttons' => [['type' => 'postback', 'title' => 'Send another report', 'payload' => 'make report'], ['type' => 'web_url', 'title' => 'Go to Uchaguzi', 'url' => 'https://uchaguzi.or.ke/']]]]]])],
            ['command' => 'not readable', 'language' => 'en',
                'answers' =>serialize([['text' =>"Oh my, I'm not programmed to understand what you're saying. Sorry!"], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'What do you want to do next?', 'buttons' => [['type' => 'postback', 'title' => 'Send another report', 'payload' => 'make report'], ['type' => 'web_url', 'title' => 'Go to Uchaguzi', 'url' => 'https://uchaguzi.or.ke/']]]]]])]
            ];
            DB::table('answers')->insert($errorMessages);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       DB::table('answers')
            ->where('command','not readable' )
            ->delete();
    }
}
