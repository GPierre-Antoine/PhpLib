<?php

use PAG\Shutdown\ShutdownEventHandler;

require_once __DIR__ . "/loader.php";

ini_set("display_errors", '0');

ShutdownEventHandler::registerErrorShutdownHandler('test',
    function () {
        echo "END OF TESTS (Error)\n";
    });

ShutdownEventHandler::cancelErrorHandler('test');
echo "in cancel\n";
trigger_error('error');