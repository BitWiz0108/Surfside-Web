<?php

use App\Kernel;

if($_SERVER['REMOTE_ADDR'] == 'x66.153.210.188') {
    echo "Maintenance mode has been activated. We will be right back.";
    exit;
}
require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
