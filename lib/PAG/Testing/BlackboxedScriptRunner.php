<?php

namespace PAG\Testing;


use http\Exception\RuntimeException;

class BlackboxedScriptRunner
{
    public static $php = "/usr/bin/php";

    public static function fetchScriptStdout($script): string
    {
        return self::executeFile($script, ' 2>/dev/null');
    }

    private static function executeFile($script, $option): string
    {
        if (php_sapi_name() !== "cli")
            throw new RuntimeException("Cannot use this function in this setting for security reasons");
        return shell_exec(self::$php ." -f $script -- $option");
    }

    public static function fetchScriptStderr($script): string
    {
        return self::executeFile($script, ' 2>&1 > /dev/null');
    }
}