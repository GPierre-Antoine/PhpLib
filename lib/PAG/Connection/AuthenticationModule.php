<?php

namespace PAG\Connection;

interface AuthenticationModule
{
    public function visitFtp(Ftp $ftp, $host, $port);

    public function visitFtpSsl(Ftp $ftp, $host, $port);

    public function visitSsh2(Ssh2 $ssh2, $host, $port);
}