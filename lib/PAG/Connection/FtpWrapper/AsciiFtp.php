<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 01/08/2018
 * Time: 20:38
 */

namespace PAG\Connection\FtpWrapper;


use PAG\Connection\FileTransferConnection;
use PAG\Connection\Ftp;

class AsciiFtp implements FileTransferConnection
{
    private $ftp;

    public function __construct(Ftp $ftp)
    {
        $this->ftp = $ftp;
    }

    public function copyLocalToRemote($local, $remote)
    {
        $this->ftp->put_ascii($local,$remote);
    }

    public function copyRemoteToLocal($remote, $local)
    {
        $this->ftp->get_ascii($remote,$local);
    }
}