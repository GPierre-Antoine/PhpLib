<?php

use PAG\Shutdown\ShutdownEventHandler;

require_once __DIR__ . "/../test_bootstrap.php";

ini_set("display_errors", '0');

ShutdownEventHandler::registerShutdownHandler('test',
    function () {
        echo "END OF TESTS\n";
    });
ShutdownEventHandler::registerErrorShutdownHandler('test',
    function () {
        echo "END OF TESTS (Error)\n";
    });

trigger_error('error');