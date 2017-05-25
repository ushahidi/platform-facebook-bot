<?php
    return [
    'welcome' => 'Hi and welcome! What do you want to do?',
    'start_buttons' =>  [
                        [
                            'type'=>'web_url',
                            'url' => 'https://www.facebook.com/Uchaguzi',
                            'title' => 'Uchaguzi-facebook'
                        ],
                        [
                            'type'=>'web_url',
                            'url' => 'https://www.ushahidi.com/ushaguzi',
                            'title' => 'Uchaguzi web'
                        ],
                        [
                            'type'=>'postback',
                            'title' => 'Post a report here',
                            'payload' =>  'make report'
                        ]
                    ],
    'start_reporting_buttons' => [
                                [
                                    'type'=>'postback',
                                    'title' => 'Yes',
                                    'payload' =>  'start'
                                ],
                                [
                                    'type' =>'postback',
                                    'title' => 'No',
                                    'payload' => 'exit'
                                ]
                            ],
    'start_reporting' => 'Next we will ask you to fill in a series of fields one-at-a-time and then once your answers are completed the post will be sent to the Uchaguzi deployment. Ready to start?',

    'title' =>'Please give me a title for your report:'
    ];