<?php

use PAG\Shutdown\ShutdownEventHandler;

require_once __DIR__ . "/loader.php";

ini_set("display_errors", '0');

ShutdownEventHandler::registerShutdownHandler('test',
    function () {
        echo "END OF TESTS\n";
    });