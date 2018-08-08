<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 24/07/2018
 * Time: 23:36
 */

namespace PAG\Application;


use PAG\Collection\Collection;

interface Application
{
    public function handleCommandLineRequest(Collection $argv): void;

    public function handleWebRequest(Collection $argv): void;
}