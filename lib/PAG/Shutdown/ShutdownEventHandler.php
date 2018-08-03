<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 03/08/18
 * Time: 10:24
 */

namespace PAG\Shutdown;


class ShutdownEventHandler
{
    private static $shutdown;
    private static $shutdown_error;
    private static $initialized = false;
    private static $context     = [];

    public static function registerShutdownHandler($identifier, $function)
    {
        self::ensureInitialization();
        self::$shutdown[$identifier] = $function;
    }

    private static function ensureInitialization(): void
    {
        if (!self::$initialized) {
            self::$initialized = true;
            self::registerShutdown();
        }
    }

    private static function registerShutdown(): void
    {
        register_shutdown_function(function () {
            self::shutdown();
        });
    }

    private static function shutdown()
    {
        if (!is_null($error = error_get_last())) {
            self::runCallbacks(self::$shutdown_error, $error);
        }
        self::runCallbacks(self::$shutdown);
    }

    private static function runCallbacks($array, $arguments = []): void
    {
        foreach ($array as $function) {
            call_user_func($function, self::$context, $arguments);
        }
    }

    public static function registerErrorShutdownHandler($identifier, $function)
    {
        self::ensureInitialization();
        self::$shutdown_error[$identifier] = $function;
    }

    public static function deleteShutdownHandler($identifier)
    {
        unset(self::$shutdown[$identifier]);
    }

    public static function deleteErrorShutdownHandler($identifier)
    {
        unset(self::$shutdown_error[$identifier]);
    }

    public static function setContext(array $context): void
    {
        self::$context = $context;
    }

}