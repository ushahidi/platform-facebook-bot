<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            ['command' => 'first hand shake', 'language' => 'en', 'reporting_flow' => false,
                'answers' => serialize([['text' => "Hello! Welcome to " . env('TITLE')], ['text' => env('AIM')], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Help us test the platform by sending us a report!', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            
            ['command' => 'what is', 'language' => 'en', 'reporting_flow' => false,
                'answers' => serialize([['text' => env('AIM')], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to send a new report?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'how information', 'language' => 'en', 'reporting_flow' => false,
                'answers' => serialize([['text' => "All reports are reviewed for accuracy and action."], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to send a new report?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])
            ],
            ['command' => 'how report', 'language' => 'en','reporting_flow' => false,
                'answers' => serialize([['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'You can send reports to' . env('NAME'). 'with twitter and facebook:', 'buttons' => [['type' => 'postback', 'title' => 'Twitter', 'payload' => 'twitter'], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Do you want to send a report in Facebook?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]]]]]])
            ],
            ['command' => 'twitter', 'language' => 'en', 'reporting_flow' => false,
                'answers' =>serialize([['text' => 'Twitter: follow ' . env('TWITTER_NAME') . 'and use #' .env('TWITTER_HASH') . 'hash tag'], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to send a new report?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'make report', 'language' => 'en', 'reporting_flow' => false,
                'answers' =>serialize([['text' => 'In a few words, describe what you would like to report.']])], 
            ['command' => 'image location', 'language' => 'en', 'reporting_flow' => true,
                'answers' => serialize([['attachment' => ['type' => 'template', 'payload' =>['template_type' => 'button', 'text' => 'Anything else? If not, do you want to add a location or image to your report?', 'buttons' => [['type' => 'postback', 'title' => 'Send my report', 'payload' => 'send'], ['type' => 'postback', 'title' => 'Add an image', 'payload' => 'add image'], ['type' => 'postback', 'title' => 'Add a location', 'payload' => 'add location']]]]]])],
            ['command' => 'not readable', 'language' => 'en', 'reporting_flow' => false,
                'answers' =>serialize([['text' =>"I am sorry, I didn't understand that"], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'What do you want to do next?', 'buttons' => [['type' => 'postback', 'title' => 'Send another report', 'payload' => 'make report'], ['type' => 'web_url', 'title' => 'Go to ' . env('NAME'), 'url' => env('PLATFORM_DEPLOYMENT')]]]]]])],
            ['command' => 'add image', 'language' => 'en', 'reporting_flow' => true,
                'answers' =>serialize([['text' => "Ok great! Just add a photo here like a normal chat, and I'll attach it to your report"]])
            ],
            ['command' => 'add location', 'language' => 'en', 'reporting_flow' => true,
                'answers' => serialize([['text' => 'Please add your location below', 'quick_replies' => [['content_type' => 'location'], ['content_type' => 'text', 'title' => 'Send my report', 'payload' => 'send']]]])
            ],
            ['command' => 'image received', 'language' => 'en', 'reporting_flow' => true,
                'answers' =>serialize([['text' => 'Ok, got it. Do you want to add a location to your report?', 'quick_replies' => [['content_type' => 'location'], ['content_type' => 'text', 'title' => 'Send my report', 'payload' => 'send']]]])
            ],
            ['command' => 'location received', 'language' => 'en',  'reporting_flow' => true,
                'answers' =>serialize([['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ok, got it. Do you want to add an image to your report?', 'buttons' => [['type' => 'postback', 'title' => 'Send my report', 'payload' => 'send'], ['type' => 'postback', 'title' => 'Add an image', 'payload' => 'add image']]]]]])
            ],
            ['command' => 'send report', 'language' => 'en', 'reporting_flow'=> true,
                'answers' =>serialize([['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Perfect! Do you want to send your report to us?', 'buttons' => [['type' => 'postback', 'title' => 'Send my report', 'payload' => 'send'], ['type' => 'postback', 'title' => 'I want to restart', 'payload' => 'make report']]]]]])
            ],
            ['command' => 'continue', 'language' => 'en',  'reporting_flow' => true,
                'answers' =>serialize([['text' => 'In a few words, describe what you would like to report.']])],
            ['command' => 'platform error', 'language' => 'en', 'reporting_flow' => false,
                'answers' => serialize([['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Hmm, we encountered a problem when sending your report, do you want to try again?', 'buttons' => [['type' => 'postback', 'title' => 'Send my report', 'payload' => 'send'], ['type' => 'postback', 'title' => 'I want to restart', 'payload' => 'make report']]]]]])
            ],
            ['command' => 'whats your name?', 'language' => 'en', 'reporting_flow' => false,
                'answers' => serialize([['text' => "I'm the " . env('TITLE')], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to get started?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'what is your name?', 'language' => 'en', 'reporting_flow' => false,
                'answers' => serialize([['text' => "I'm the " . env('TITLE')], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to get started?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'how old are you?', 'language' => 'en', 'reporting_flow' => false,
                'answers' => serialize([['text' => "I'm not that old, so I can only do a few things."], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to get started?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'what can you do?', 'language' => 'en', 'reporting_flow' => false,
                'answers' => serialize([['text' => "I can help you report to " . env('NAME')], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to get started?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'not readable', 'language' => 'en', 'reporting_flow' => false,
                'answers' =>serialize([['text' =>"Sorry, I'm just a bot built for reporting, so my vocabulary is pretty limited."], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'What do you want to do next?', 'buttons' => [['type' => 'postback', 'title' => 'Send another report', 'payload' => 'make report'], ['type' => 'web_url', 'title' => 'Go to ' . env('NAME'), 'url' => env('PLATFORM_DEPLOYMENT')]]]]]])],
            ['command' => 'not readable', 'language' => 'en', 'reporting_flow' => false,
                'answers' =>serialize([['text' =>"Oh my, I'm not programmed to understand what you're saying. Sorry!"], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'What do you want to do next?', 'buttons' => [['type' => 'postback', 'title' => 'Send another report', 'payload' => 'make report'], ['type' => 'web_url', 'title' => 'Go to ' . env('NAME'), 'url' => env('PLATFORM_DEPLOYMENT')]]]]]])],
            ['command' => 'help', 'language' => 'en', 'reporting_flow' => false,
                'answers' => serialize([['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ok, what do you want to know?', 'buttons' => [['type' => 'postback', 'title' => 'What is '. env('NAME') . '?','payload'=>'what is'], ['title' => 'How do I report?', 'type'=>'postback', 'payload' =>'how report'], ['type' => 'postback',  'title' => 'How is my info used?', 'type'=>'postback', 'payload'=>'how information']]]]]])],
            ['command' => 'submit', 'language' => 'en', 'reporting_flow'=> true, 'answers' => serialize([['attachment' => 
                        ['type' => 'template', 'payload' => ['template_type' => 'generic', 'elements'=>[
                        ['title' =>'Your report has been saved. Nice work!', 'subtitle' => 'A moderator will check your report before it is published to ' . env('NAME'), 'buttons' => [['type'=> 'element_share', 'share_contents'=> ['attachment'=> ['type'=> 'template', 'payload'=> ['template_type'=> 'generic', 'image_aspect_ratio' => 'square', 'elements'=>[['title' => env('NAME'), 'subtitle'=> env('AIM'), 'image_url'=> env('LOGO'),'default_action' => ['type' => 'web_url', 'url' => env('FACEBOOK_PAGE_URL')], 'buttons' => [['type' => 'web_url', 'title' => 'Check out ' . env('TITLE'), 'url' => env('FACEBOOK_PAGE_URL')]]]]]]]]]]]]]], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'What do you want to do next?', 'buttons' => [['type' => 'postback', 'title' => 'Send another report', 'payload' => 'make report'], ['type' => 'web_url', 'title' => 'Go to ' . env('NAME'), 'url' => env('PLATFORM_DEPLOYMENT')]]]]]])],
            ['command' => 'no location', 'language' => 'en', 'reporting_flow' => true, 'answers'=> serialize([['text' => 'I had a problem fetching location, please write your location in text below']])]
        ];
        DB::table('answers')->delete();
        DB::table('answers')->insert($data);

        }
    }