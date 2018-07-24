<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 21/07/2018
 * Time: 11:17
 */

namespace Psr\Log;


/**
 * Describes a logger-aware instance
 */
interface LoggerAwareInterface
{
    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger);
}