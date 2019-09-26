<?php

use PAG\Shutdown\ShutdownEventHandler;

require_once __DIR__ . "/loader.php";

ini_set("display_errors", '0');

ShutdownEventHandler::registerErrorShutdownHandler('test',
    function ($context, $error) {
        echo $context['message'] . PHP_EOL . $error['message'] . PHP_EOL;
    });
ShutdownEventHandler::setContext(['message' => "ok context"]);
trigger_error("ok error");