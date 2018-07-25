<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 24/07/2018
 * Time: 23:36
 */

namespace PAG\Application;


use PAG\Collection\Collection;
use Psr\Log\LoggerAwareInterface;

interface Application extends LoggerAwareInterface
{
    public function start(): void;
    public function handleCli(Collection $argv) : void;
    public function handleWeb(Collection $argv) : void;
}