<?php

use iansltx\DialogflowBridge\{Question, Answer, Middleware\DoublePass, Router};

return function(\Pimple\Container $c, $env) {
    $c[DoublePass::class] = function($c) {
        return new DoublePass(Router::build($c, [
            'schedule' => \BuildABot\App\ScheduleHandler::class
        ], function(Question $question, Answer $answer) : Answer {
            return $answer->withSpeechAndText("Sorry, I don't know how to help you with that.");
        }));
    };
    $c[\BuildABot\App\ScheduleHandler::class] = function () {
        return new \BuildABot\App\ScheduleHandler(\iansltx\JoindInClient\Client::create());
    };
};
