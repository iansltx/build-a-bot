<?php

use iansltx\ApiAiBridge\Question, iansltx\ApiAiBridge\Answer;

return function(\Pimple\Container $c, $env) {
    $c[\iansltx\ApiAiBridge\Middleware\DoublePass::class] = function() {
        return new \iansltx\ApiAiBridge\Middleware\DoublePass(\iansltx\ApiAiBridge\Router::buildFromClosureArray([
            'schedule' => function(Question $question, Answer $answer) {
                date_default_timezone_set('America/Los_Angeles');
                $date = $question->getParam('date', date('Y-m-d'));

                $whatsHappening = "what's happening";

                if ($date === date('Y-m-d')) {
                    $relative = 'today';
                } elseif ($date === date('Y-m-d', time() - 86400)) {
                    $relative = 'yesterday';
                } elseif ($date === date('Y-m-d', time() + 86400)) {
                    $relative = 'tomorrow';
                } elseif (strtotime($date) > time() && strtotime($date) <= time() + 86400 * 7) {
                    $relative = 'on ' . date('l', strtotime($date)); // day of week
                } else {
                    if (strtotime($date) < time()) {
                        $whatsHappening = 'what happened';
                    }
                    $relative = 'on the ' . date('jS', strtotime($date)); // e.g. the 5th
                }

                return $answer->withSpeechAndText("Sorry, but I'm not sure $whatsHappening $relative quite yet.");
            }
        ], function(Question $question, Answer $answer) : Answer {
            return $answer->withSpeechAndText("Sorry, I don't know how to help you with that.");
        }));
    };
};
