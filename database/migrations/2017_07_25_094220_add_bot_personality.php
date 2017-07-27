<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBotPersonality extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $personalities = [
            ['command' => 'whats your name?', 'language' => 'en',
                'answers' => serialize([['text' => "I'm the Uchaguzi reporting bot."], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to get started?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'what is your name?', 'language' => 'en',
                'answers' => serialize([['text' => "I'm the Uchaguzi reporting bot."], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to get started?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'how old are you?', 'language' => 'en',
                'answers' => serialize([['text' => "I'm not that old, so I can only do a few things."], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to get started?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'what can you do?', 'language' => 'en',
                'answers' => serialize([['text' => "I can help you report what you see during the Kenyan election."], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to get started?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])]
            ];

        DB::table('answers')->insert($personalities);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('answers')
            ->where('command','whats your name?' )
            ->orWhere('command', 'what is your name?')
            ->orWhere('command', 'how old are you?')
            ->orWhere('command', 'what can you do?')
            ->delete();
    }
}
