<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHelpMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('answers')->insert(
            ['command' => 'help', 'language' => 'en', 
                'answers' => serialize([['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ok, what do you want to know?', 'buttons' => [['type' => 'postback', 'title' => 'What is Uchaguzi?','payload'=>'what is'], ['title' => 'How do I report?', 'type'=>'postback', 'payload' =>'how report'], ['type' => 'postback',  'title' => 'How is my info used?', 'type'=>'postback', 'payload'=>'how information']]]]]])]);  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('answers')->where('command', 'help')->delete();
    }
}
