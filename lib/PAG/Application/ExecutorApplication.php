<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 12:32
 */

namespace PAG\Application;


use \RuntimeException;
use PAG\Collection\Collection;
use PAG\Executor\Executor;
use PAG\Log\ConsoleLogger;

class ExecutorApplication implements Application
{
    private $executor;

    public function __construct(Executor $executor)
    {
        $this->executor = $executor;
        $this->executor->setLogger(new ConsoleLogger());
    }

    public function handleCommandLineRequest(Collection $argv): void
    {
        $executor = $this->executor;
        $executor(...$argv->getArrayCopy());
    }

    public function handleWebRequest(Collection $argv): void
    {
        throw new RuntimeException("Can't be ran via web interface");
    }
}