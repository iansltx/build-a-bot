<?php

use iansltx\DialogflowBridge\{Question, Answer, Middleware\DoublePass, Router};

return function(\Pimple\Container $c, $env) {
    $c[DoublePass::class] = function() {
        return new DoublePass(Router::buildFromClosureArray([
            'schedule' => function(Question $question, Answer $answer) {
                date_default_timezone_set('America/New_York');
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
                    $relative = 'on the ' . date('jS', strtotime($date)); // e.g. the 13th
                }

                return $answer->withSpeechAndText("Sorry, but I'm not sure $whatsHappening $relative quite yet.");
            }
        ], function(Question $question, Answer $answer) : Answer {
            return $answer->withSpeechAndText("Sorry, I don't know how to help you with that.");
        }));
    };
};
