<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 02/08/18
 * Time: 10:09
 */

namespace PAG\Connection\RemoteFileTransferTool;

use PAG\Connection\RemoteFileTransferTool;

class NameControllingFileTransferTool extends FileTransferToolDecorator
{

    private $remoteDirectoryForInput;
    private $remoteDirectoryForOutput;

    public function __construct(
        RemoteFileTransferTool $fileTransferTool,
        $remoteDirectoryForInput,
        $remoteDirectoryForOutput
    ) {
        parent::__construct($fileTransferTool);
        $this->remoteDirectoryForInput  = $remoteDirectoryForInput;
        $this->remoteDirectoryForOutput = $remoteDirectoryForOutput;
    }

    public function copyLocalToRemote($local, $remote): void
    {
        parent::copyLocalToRemote($local, $this->alterNameForOutput($remote));
    }

    private function alterNameForOutput($filename): string
    {
        return $this->remoteDirectoryForOutput . basename($filename);
    }

    public function copyRemoteToLocal($remote, $local): void
    {
        parent::copyRemoteToLocal($this->alterNameForInput($remote), $local);
    }

    private function alterNameForInput($filename): string
    {
        return $this->remoteDirectoryForInput . basename($filename);
    }
}