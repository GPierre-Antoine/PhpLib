<?php

namespace PAG\Testing;


use http\Exception\RuntimeException;

class BlackboxedScriptRunner
{
    public static $php = "/usr/bin/php";

    public static function fetchScriptStdout($script) : string
    {
        return self::secureExecuteFile($script, ' 2>/dev/null');
    }

    private static function secureExecuteFile($script, $option) : string
    {
        if (!self::checkContextReliabilty()) {
            throw new RuntimeException("Cannot use this function in this setting for security reasons");
        }

        return shell_exec(self::$php." -f $script -- $option");
    }

    private static function checkContextReliabilty() : bool
    {
        return php_sapi_name() === "cli";
    }

    public static function fetchScriptStderr($script) : string
    {
        return self::secureExecuteFile($script, ' 2>&1 > /dev/null');
    }
}