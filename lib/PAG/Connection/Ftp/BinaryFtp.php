<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 01/08/2018
 * Time: 20:38
 */

namespace PAG\Connection\Ftp;


use PAG\Connection\Ftp;
use PAG\Connection\RemoteFileTransferTool;

class BinaryFtp implements RemoteFileTransferTool
{
    private $ftp;

    public function __construct(Ftp $ftp)
    {
        $this->ftp = $ftp;
    }

    public function copyLocalToRemote($local, $remote)
    {
        $this->ftp->put_binary($local, $remote);
    }

    public function copyRemoteToLocal($remote, $local)
    {
        $this->ftp->get_binary($remote, $local);
    }
}