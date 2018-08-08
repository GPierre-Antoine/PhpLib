<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 14:56
 */

namespace PAG\Executor;


use PAG\Traits\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use RuntimeException;

class Executor implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __invoke(...$args)
    {
        try {
            if (!count($args)) {
                throw new RuntimeException("Nothing to do");
            }
        }
        catch (\Exception $exception) {
            $this->getLogger()->error($exception->getMessage());
        }
    }
}