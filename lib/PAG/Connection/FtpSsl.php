<?php

namespace PAG\Connection;

class FtpSsl extends Ftp
{
    public function connect($host, $port, AuthenticationModule $module) : void
    {
        $this->setConnection($module->visitFtpSsl($this, $host, $port));
        $this->setModePassive();
    }
}