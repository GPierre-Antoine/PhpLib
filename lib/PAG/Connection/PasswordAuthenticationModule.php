<?php

namespace PAG\Connection;

use RuntimeException;

class PasswordAuthenticationModule implements AuthenticationModule
{
    use Ssh2Auth;

    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    function visitFtp(Ftp $ftp, $host, $port)
    {
        $connection = ftp_connect($host, $port);
        if (!$connection) {
            throw new RuntimeException("FTP : Could not connect to $host:$port");
        }
        ftp_login($connection, $this->username, $this->password);

        return $connection;
    }

    function visitFtpSsl(Ftp $ftp, $host, $port)
    {
        if (!function_exists('ftp_ssl_connect')) {
            $string  = 'ftp_ssl_connect';
            $url_ize = str_replace('_', '-', $string);
            throw new RuntimeException("The <a target='_blank' href='http://php.net/manual/en/function.$url_ize.php'>[$string]</a>" .
                                       " function doesn't exist in this environnment. Please switch to regular ftp.");
        }
        $connection = ftp_ssl_connect($host, $port);
        if (!$connection) {
            throw new RuntimeException("FTP : Could not connect to $host:$port");
        }
        ftp_login($connection, $this->username, $this->password);

        return $connection;
    }

    public function visitSsh2(Ssh2 $ssh2, $host, $port)
    {

        $this->assertCanUseSSH2();
        $connection = $this->ssh2Connect($host, $port);
        $this->checkFingerPrint($ssh2, $connection);
        $this->ssh2Identify($connection);
        return $connection;
    }

    private function ssh2Identify($connection)
    {
        ssh2_auth_password($connection, $this->username, $this->password);
    }
}