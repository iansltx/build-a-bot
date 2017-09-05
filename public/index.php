<?php

require __DIR__ . '/../vendor/autoload.php';

if (php_sapi_name() == 'cli-server') {
    if (!isset($_SERVER['PATH_INFO'])) {
        $_SERVER['SCRIPT_NAME'] = ''; // workaround for PHP built-in web server extension handling
    }
}

$app = new Slim\App();

call_user_func(require __DIR__ . '/../bootstrap/services.php', $app->getContainer(), $_SERVER + $_ENV);
$app->group('/', require __DIR__ . '/../bootstrap/routes.php');

$app->run();
