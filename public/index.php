<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
$_SERVER['HTTPS'] = 'on';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
<?php
$_SERVER['SERVER_PORT'] = getenv('PORT') ?: 8080;
server->listen('0.0.0.0', $_SERVER['SERVER_PORT']);
