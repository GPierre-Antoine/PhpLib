<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 30/08/18
 * Time: 12:01
 */

namespace PAG\Connection;

use PAG\Connection\Utilitary\NetrcParser;
use RuntimeException;

class NetrcAuthentication implements AuthenticationModule
{
    private $file;
    private $parser;


    public function __construct($file = "~/.netrc")
    {
        $this->file   = $file;
        $netrcContent = $this->readFile();
        $this->parser = new NetrcParser($netrcContent);
    }

    private function readFile(): string
    {
        $netrcContent = file_get_contents($this->file);
        if (!$netrcContent) {
            throw new RuntimeException("Could not read content from $this->file");
        }
        return $netrcContent;
    }

    public function visitFtp(Ftp $ftp, $host, $port)
    {
        return $this->makePasswordAuthenticationModule($host)->visitFtp($ftp, $host, $port);
    }

    private function makePasswordAuthenticationModule($host): PasswordAuthenticationModule
    {
        list($username, $password) = $this->parser->getCouple($host);
        return new PasswordAuthenticationModule($username, $password);
    }

    public function visitFtpSsl(Ftp $ftp, $host, $port)
    {
        return $this->makePasswordAuthenticationModule($host)->visitFtpSsl($ftp, $host, $port);
    }

    public function visitSsh2(Ssh2 $ssh2, $host, $port)
    {
        throw new RuntimeException("No Netrc Authentication with SSH");
    }
}