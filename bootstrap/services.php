<?php

use iansltx\ApiAiBridge\Question, iansltx\ApiAiBridge\Answer;

return function(\Pimple\Container $c, $env) {
    $c[\iansltx\ApiAiBridge\Middleware\DoublePass::class] = function($c) {
        return new \iansltx\ApiAiBridge\Middleware\DoublePass(\iansltx\ApiAiBridge\Router::build($c, [
            'schedule' => \BuildABot\App\ScheduleHandler::class
        ], function(Question $question, Answer $answer) : Answer {
            return $answer->withSpeechAndText("Sorry, I don't know how to help you with that.");
        }));
    };
    $c[\BuildABot\App\ScheduleHandler::class] = function () {
        return new \BuildABot\App\ScheduleHandler(\iansltx\PNWPHP2017ScheduleClient\Client::create());
    };
};};
