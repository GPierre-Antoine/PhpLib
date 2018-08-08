<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 25/07/18
 * Time: 14:16
 */

namespace PAG\Traits;


use Psr\Log\LoggerInterface;

trait LoggerUserTrait
{
    /**
     * @type LoggerInterface
     */
    protected $logger;


    public function getLogger()
    {
        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}