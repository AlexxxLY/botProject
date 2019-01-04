<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class ExampleConversation extends Conversation
{
    /**
     * First question
     */
    public function askReason()
    {
        $question = Question::create("PRIVAT BANK exchange course")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Get USD')->value(0),
                Button::create('Get EUR')->value(1),
                Button::create('Get RUB')->value(2),
                Button::create('Get BTC')->value(3),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {

                $value = $answer->getValue();
                $curr = json_decode(file_get_contents('https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5'));
                
                $this->say(round($curr[$value]->sale, 2));

                // if ($answer->getValue() === 'joke') {
                //     $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
                //     $this->say($joke->value->joke);
                // } 
                // if ($answer->getValue() === 'valut'){
                    
                //     $str = file_get_contents('https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5');
                //     $dec = json_decode($str);

                //     $this->say(round($dec[0]->sale, 2));

                // }
                // else {
                //     $this->say(Inspiring::quote());
                // }
            }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
    }
}
