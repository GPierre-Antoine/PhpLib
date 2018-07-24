<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 21/07/2018
 * Time: 11:17
 */

namespace Psr\Log;


/**
 * Describes log levels
 */
class LogLevel
{
    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';
}