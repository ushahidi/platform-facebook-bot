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
        $startButtons = [
            [
                'type' => 'web_url',
                'url' => 'https://www.facebook.com/Uchaguzi',
                'title' => 'Uchaguzi-facebook'
            ],
            [
                'type' => 'web_url',
                'url' => 'https://www.ushahidi.com/ushaguzi',
                'title' => 'Uchaguzi web'
            ],
            [
                'type' => 'postback',
                'title' => 'Post a report here',
                'payload' => 'make report'
            ]
        ];

        $data = [
            ['command' => 'first hand shake', 'answer' => json_encode(['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Hi and welcome! What do you want to do?', 'buttons' => $startButtons, ]]])],
            ['command' => 'exit', 'answer' => json_encode(['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'What do you want to do next?', 'buttons' => $startButtons, ]]])],
            ['command' => 'make report', 'answer' => json_encode(['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Next we will ask you to fill in a series of fields one-at-a-time and then once your answers are completed the post will be sent to the Uchaguzi deployment. Ready to start?', 'buttons' => [
                ['type' => 'postback', 'title' => 'Yes', 'payload' => 'start'],
                ['type' => 'postback', 'title' => 'No', 'payload' => 'exit']
            ]]]])],
            ['command' => 'start', 'answer' => json_encode(['text' => 'Please give me a title for your report:'])],
            ['command' => 'not readable', 'answer' => json_encode(['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Sorry, I did not understand that, please choose of the following:', 'buttons' => $startButtons, ]]])],
            ['command' => 'description', 'answer' => json_encode(['text' => 'What do you want to report? Please add a description:'])],
            ['command' => 'image', 'answer' => json_encode(['text' => 'Please add an image to your report:'])],
            ['command' => 'location', 'answer' => json_encode(['text' => 'Please add your location', 'quick_replies' => [
                ['content_type' => 'location']
            ]])],
            ['command' => 'finished', 'answer' => json_encode(['attachment' => ['type' => 'template', 'payload' => ['template_type' => 'button', 'text' => 'Thank you! Do you want to send your report?:', 'buttons' => ['type' => 'postback', 'title' => 'Yes, please send', 'payload' => 'send'],
                ['type' => 'postback', 'title' => 'No, delete and go back to start', 'payload' => 'exit']
            ]]])]
        ];

        DB::table('answers')->delete();
        DB::table('answers')->insert($data);

        }
    }

