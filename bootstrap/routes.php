<?php

use Slim\Http\Request, Slim\Http\Response;

return function() { /** @var \Slim\App $this */
    $this->get('bot', function(Request $request, Response $response) {
        return $response->write(
            str_replace('{{ API_AI_KEY }}', getenv('API_AI_KEY'), file_get_contents(__DIR__ . '/../templates/bot.html')
        ));
    });
};
