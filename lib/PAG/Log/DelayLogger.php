<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 27/07/18
 * Time: 14:12
 */

namespace PAG\Log;


use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class DelayLogger implements LoggerInterface
{
    use LoggerTrait;

    private $array;

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $this->array[] = [$level, $message, $context];
    }

    /**
     * @return mixed
     */
    public function getArray()
    {
        return $this->array;
    }
}