<?php

namespace PAG\Connection;

class FtpSsl extends Ftp
{
    public function connect($host, $port, AuthenticationModule $module)
    {
        $this->setConnection($module->visitFtpSsl($this, $host, $port));
        $this->setMode(Ftp::MODE_PASSIVE);
    }
}