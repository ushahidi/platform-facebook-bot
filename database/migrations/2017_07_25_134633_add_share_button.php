<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShareButton extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $answer = [
            'command' => 'submit',
            'language' => 'en', 
            'answers' => serialize([
                    ['attachment' => 
                        [
                            'type' => 'template', 
                            'payload' => [
                                'template_type' => 'generic',
                                'elements'=>[
                                    [
                                        'title' =>'Your report has been saved. Nice work!', 
                                        'subtitle' => 'A moderator will check your report before it is published to Uchaguzi',
                                        'buttons' => [
                                            [
                                                'type'=> 'element_share', 
                                                'share_contents'=> [
                                                    'attachment'=> [
                                                        'type'=> 'template',
                                                        'payload'=> [
                                                            'template_type'=> 
                                                            'generic',
                                                            'image_aspect_ratio' => 'square',
                                                            'elements'=> [
                                                                [
                                                                    'title' => 'Uchaguzi',
                                                                    'subtitle'=> 'Helping Kenya have a free, fair, peaceful and credible general election',
                                                                    'image_url'=> env('LOGO'),
                                                                    'default_action' => [
                                                                        'type' => 'web_url',
                                                                        'url' => env('FACEBOOK_PAGE_URL')
                                                                    ],
                                                                    'buttons' => [
                                                                        [
                                                                            'type' => 'web_url',
                                                                            'title' => 'Check out Uchaguzi bot',
                                                                            'url' => env('FACEBOOK_PAGE_URL')
                                                                        ]
                                                                    ]
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]  
                            ]
                        ]
                    ], 
                    ['attachment' => 
                        [
                            'type' => 'template',
                            'payload' => [
                                'template_type' => 'button',
                                'text' => 'What do you want to do next?',
                                'buttons' => [
                                    [
                                        'type' => 'postback',
                                        'title' => 'Send another report',
                                        'payload' => 'make report'
                                    ],
                                    [
                                        'type' => 'web_url',
                                        'title' => 'Go to Uchaguzi',
                                        'url' => 'https://uchaguzi.or.ke/'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ])];

        DB::table('answers')
            ->where('command', 'submit')
            ->where('language', 'en')
            ->update($answer);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $answer = [
            'command' => 'submit',
            'language' => 'en',
            'answers' =>serialize([
                [
                    'text' => 'Your report has been saved. Nice work! A moderator will check your report before it is published to https://uchaguzi.or.ke/.'
                ],
                [
                    'attachment' => [
                        'type' => 'template',
                        'payload' => [
                            'template_type' => 'button',
                            'text' => 'What do you want to do next?',
                            'buttons' => [
                                [
                                    'type' => 'postback',
                                    'title' => 'Send another report',
                                    'payload' => 'make report'
                                ],
                                [
                                    'type' => 'web_url',
                                    'title' => 'Go to Uchaguzi',
                                    'url' => 'https://uchaguzi.or.ke/'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        )];

            DB::table('answers')
                ->where('command', 'submit')
                ->where('language', 'en')
                ->update($answer);
    }
}
