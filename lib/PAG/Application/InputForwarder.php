<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 12:19
 */

namespace PAG\Application;


use PAG\Collection\Collection;

class InputForwarder
{
    private $application;

    private function __construct(Application $application)
    {
        $this->application = $application;
    }

    public static final function startApplication(Application $handle)
    {
        $app = new InputForwarder($handle);
        if (php_sapi_name() == "cli") {
            $app->handleCli();
        }
        else {
            $app->handleWeb();
        }
    }

    protected function handleCli()
    {
        $argv = $this->extractCliRequest();
        $this->application->handleCommandLineRequest($argv);
    }

    protected function extractCliRequest()
    {
        global $argv;
        $request = new Collection($argv);
        $request->shift();
        return $request;
    }

    protected function handleWeb()
    {
        $argv = $this->extractWebRequest();
        $this->application->handleWebRequest($argv);
    }

    protected function extractWebRequest()
    {
        $argv    = $_REQUEST['ARGV'];
        $request = new Collection(preg_split("/(%20)| |=/", $argv));
        return $request;
    }
}