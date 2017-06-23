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
            ['command' => 'first hand shake', 'language' => 'en',
                'answers' => serialize([['text' => "Hello! Welcome to Uchaguzi Citizen Reporter."], ['text' => "Uchaguzi's aim is to help Kenya have a free, fair, peaceful, and credible general election."], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Keep an eye on the vote and submit any reports of issues that impact the election', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])
            ],
            ['command' => 'what is', 'language' => 'en',
                'answers' => serialize([['text' => "Uchaguzi is a partnership committed to increased transparency and accountability in Kenya's General Election through active citizen participation."], ['text' => ' Linda Kura Yako!'], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to send a new report?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'how information', 'language' => 'en',
                'answers' => serialize([['text' => "All reports are reviewed for accuracy and action. Teams of trained volunteers will work with our partners to verify and amplify reports. Our criteria is to give an accurate and citizen-driven picture of what is happening during the Kenyan Elections."], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to send a new report?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])
            ],
            ['command' => 'how report', 'language' => 'en',
                'answers' => serialize([['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'You can send reports to Uchaguzi a few ways:', 'buttons' => [['type' => 'postback', 'title' => 'Text message(SMS)', 'payload' => 'sms'], ['type' => 'postback', 'title' => 'Twitter', 'payload' => 'twitter'], ['type' => 'postback', 'title' => 'Email', 'payload' => 'email']]]]], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'You can also send a report in Facebook Messenger. Want to get started?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])
            ],
            ['command' => 'sms', 'language' => 'en',
                'answers' =>serialize([['text' => 'Send a text message (SMS) on 20166 (short code) from within Kenya'], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to send a new report?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'twitter', 'language' => 'en',
                'answers' =>serialize([['text' => 'Twitter: follow @uchaguzi and use #uchaguzi hash tag'], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to send a new report?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'email', 'language' => 'en',
                'answers' =>serialize([['text' => 'Email reports.uchaguzi@gmail.com'], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ready to send a new report?', 'buttons' => [['type' => 'postback', 'title' => 'Send a report', 'payload' => 'make report']]]]]])],
            ['command' => 'make report', 'language' => 'en',
                'answers' =>serialize([['text' => 'In a few words, describe what you would like to report.']])], 
            ['command' => 'image location', 'language' => 'en',
                'answers' => serialize([['attachment' => ['type' => 'template', 'payload' =>['template_type' => 'button', 'text' => 'Anything else? If not, do you want to add a location or image to your report?', 'buttons' => [['type' => 'postback', 'title' => 'Send my report', 'payload' => 'send'], ['type' => 'postback', 'title' => 'Add an image', 'payload' => 'add image'], ['type' => 'postback', 'title' => 'Add a location', 'payload' => 'add location']]]]]])],
            ['command' => 'not readable', 'language' => 'en',
                'answers' =>serialize([['text' =>"I am sorry, I didn't understand that"], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'What do you want to do next?', 'buttons' => [['type' => 'postback', 'title' => 'Send another report', 'payload' => 'make report'], ['type' => 'web_url', 'title' => 'Go to Uchaguzi', 'url' => 'https://uchaguzi.or.ke/']]]]]])],
            ['command' => 'add image', 'language' => 'en',
                'answers' =>serialize([['text' => "Ok great! Just add a photo here like a normal chat, and I'll attach it to your report"]])
            ],
            ['command' => 'add location', 'language' => 'en',
                'answers' => serialize([['text' => 'Please add your location below', 'quick_replies' => [['content_type' => 'location'], ['content_type' => 'text', 'title' => 'Send my report', 'payload' => 'send']]]])
            ],
            ['command' => 'image received', 'language' => 'en',
                'answers' =>serialize([['text' => 'Ok, got it. Do you want to add a location to your report?', 'quick_replies' => [['content_type' => 'location'], ['content_type' => 'text', 'title' => 'Send my report', 'payload' => 'send']]]])
            ],
            ['command' => 'location received', 'language' => 'en',
                'answers' =>serialize([['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Ok, got it. Do you want to add an image to your report?', 'buttons' => [['type' => 'postback', 'title' => 'Send my report', 'payload' => 'send'], ['type' => 'postback', 'title' => 'Add an image', 'payload' => 'add image']]]]]])
            ],
            ['command' => 'submit', 'language' => 'en',
                'answers' =>serialize([['text' => 'Your report has been saved. Nice work! A moderator will check your report before it is published to https://uchaguzi.or.ke/.'], ['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'What do you want to do next?', 'buttons' => [
                    ['type' => 'postback', 'title' => 'Send another report', 'payload' => 'make report'], ['type' => 'web_url', 'title' => 'Go to Uchaguzi', 'url' => 'https://uchaguzi.or.ke/']]]]]])
            ],
            ['command' => 'send report', 'language' => 'en',
                'answers' =>serialize([['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Perfect! Do you want to send your report to us?', 'buttons' => [['type' => 'postback', 'title' => 'Send my report', 'payload' => 'send'], ['type' => 'postback', 'title' => 'I want to restart', 'payload' => 'make report']]]]]])
            ],
            ['command' => 'continue', 'language' => 'en',
                'answers' =>serialize([['text' => 'In a few words, describe what you would like to report.']])],
            ['command' => 'platform error', 'language' => 'en',
                'answers' => serialize([['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Hmm, we encountered a problem when sending your report, do you want to try again?', 'buttons' => [['type' => 'postback', 'title' => 'Send my report', 'payload' => 'send'], ['type' => 'postback', 'title' => 'I want to restart', 'payload' => 'make report']]]]]])
            ]
        ];

        DB::table('answers')->delete();
        DB::table('answers')->insert($data);

        }
    }