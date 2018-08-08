<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 12:32
 */

namespace PAG\Application;


use http\Exception\RuntimeException;
use PAG\Collection\Collection;

class ExecutorApplication implements Application
{

    public function handleCommandLineRequest(Collection $argv): void
    {
        echo $argv;
    }

    public function handleWebRequest(Collection $argv): void
    {
        throw new RuntimeException("Can't be ran via web interface");
    }
}