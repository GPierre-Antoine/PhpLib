<?php

use PAG\Shutdown\ShutdownEventHandler;

require_once __DIR__ . "/../test_bootstrap.php";

ini_set("display_errors", '0');

ShutdownEventHandler::registerErrorShutdownHandler('test',
    function () {
        echo "END OF TESTS (Error)\n";
    });

ShutdownEventHandler::cancelErrorHandler('test');

trigger_error('error');