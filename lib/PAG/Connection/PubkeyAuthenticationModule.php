<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 01/08/18
 * Time: 10:52
 */

namespace PAG\Connection;

use RuntimeException;

class PubkeyAuthenticationModule implements AuthenticationModule
{
    use Ssh2Auth;

    private $username;
    private $pubkey_file;
    private $privkey_file;

    /**
     * pubkey_authentication_module constructor.
     * @param $username
     * @param $pubkey_file
     * @param $privkey_file
     */
    public function __construct($username, $pubkey_file, $privkey_file)
    {
        $this->username     = $username;
        $this->pubkey_file  = $pubkey_file;
        $this->privkey_file = $privkey_file;
    }


    public function visitFtp(Ftp $ftp, $host, $port)
    {
        throw new RuntimeException("No Pubkey Authentication for simple ftp");
    }

    public function visitFtpSsl(Ftp $ftp, $host, $port)
    {
        throw new RuntimeException("No Pubkey Authentication for ftp secure");
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
        \ssh2_auth_pubkey_file($connection, $this->username, $this->pubkey_file, $this->privkey_file);
    }
}