<?php

use iansltx\ApiAiBridge\Question, iansltx\ApiAiBridge\Answer;

return function(\Pimple\Container $c, $env) {
    $c[\iansltx\ApiAiBridge\Middleware\DoublePass::class] = function() {
        return new \iansltx\ApiAiBridge\Middleware\DoublePass(\iansltx\ApiAiBridge\Router::buildFromClosureArray([
            // no skills yet
        ], function(Question $question, Answer $answer) : Answer {
            return $answer->withSpeechAndText("Sorry, I don't know how to help you with that.");
        }));
    };
};
