<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 02/08/18
 * Time: 10:30
 */

namespace PAG\Connection\RemoteFileTransferTool;


use PAG\Connection\RemoteFileTransferTool;

abstract class FileTransferToolDecorator implements RemoteFileTransferTool
{

    private $fileTransferTool;

    public function __construct(RemoteFileTransferTool $fileTransferTool)
    {
        $this->fileTransferTool = $fileTransferTool;
    }

    public function copyLocalToRemote($local, $remote): void
    {
        $this->fileTransferTool->copyLocalToRemote($local, $remote);
    }

    public function copyRemoteToLocal($remote, $local): void
    {
        $this->fileTransferTool->copyRemoteToLocal($remote, $local);
    }
}