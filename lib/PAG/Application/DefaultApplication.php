<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 25/07/18
 * Time: 14:16
 */

namespace PAG\Application;


use PAG\Collection\Collection;
use Psr\Log\LoggerInterface;

abstract class DefaultApplication implements Application
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public final function start(): void
    {
        if (php_sapi_name() == "cli") {
            global $argv;
            $request = new Collection($argv);
            $request->shift();
            $this->handleCli($request);
        }
        else {
            $uri     = $_SERVER['REQUEST_URI'];
            $request = new Collection(preg_split("/\?|&|(%20)|=/", $uri));
            $request->shift();
            $this->handleWeb($request);
        }
    }

    public abstract function handleCli(Collection $argv): void;

    public abstract function handleWeb(Collection $argv): void;

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}