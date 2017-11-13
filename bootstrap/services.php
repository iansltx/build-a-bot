<?php

use iansltx\DialogflowBridge\{Question, Answer, Middleware\DoublePass, Router};

return function(\Pimple\Container $c, $env) {
    $c[DoublePass::class] = function() {
        return new DoublePass(Router::buildFromClosureArray([
            // no skills yet
        ], function(Question $question, Answer $answer) : Answer {
            return $answer->withSpeechAndText("Sorry, I don't know how to help you with that.");
        }));
    };
};
