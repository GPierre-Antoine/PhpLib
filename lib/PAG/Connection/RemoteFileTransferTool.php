<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 01/08/18
 * Time: 17:53
 */

namespace PAG\Connection;


interface RemoteFileTransferTool
{
    public function copyLocalToRemote($local, $remote):void;

    public function copyRemoteToLocal($remote, $local):void;
}