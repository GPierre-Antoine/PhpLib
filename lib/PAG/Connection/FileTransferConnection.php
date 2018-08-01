<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 01/08/18
 * Time: 17:53
 */

namespace PAG\Connection;


interface FileTransferConnection
{
    public function copyLocalToRemote($local, $remote);

    public function copyRemoteToLocal($remote, $local);
}