<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 01/08/18
 * Time: 10:18
 */

namespace PAG\Connection;


use PAG\Connection\Exception\FailedToChmod;
use PAG\Connection\Exception\FailedToDelete;
use PAG\Connection\Exception\FailedToGetFile;
use PAG\Connection\Exception\FailedToMkdir;
use PAG\Connection\Exception\FailedToPutFile;
use PAG\Connection\Exception\FailedToRename;
use PAG\Connection\Exception\FailedToRmDir;
use PAG\Connection\Exception\FailedToSymlink;
use RuntimeException;

class Sftp implements Ssh2, FileTransferConnection
{
    private $connection;
    private $sftp;
    private $fingerPrint;

    public function connect($host, $port, AuthenticationModule $module, $fingerprint = null) : void
    {
        $this->fingerPrint = $fingerprint;
        $this->setConnection($module->visitSsh2($this, $host, $port));
    }

    private function setConnection($connection) : void
    {
        $this->connection = $connection;
        $this->buildSftpConnection();
    }

    private function buildSftpConnection() : void
    {
        $this->sftp = ssh2_sftp($this->connection);
        if (!$this->sftp) {
            throw new \RuntimeException("Could not initialize SFTP subsystem.");
        }
    }

    public function __destruct()
    {
        if ($this->connection) {
            $this->disconnect();
        }
    }

    public function disconnect() : void
    {
        if (!$this->connection) {
            return;
        }
        $this->connection = null;
        $this->sftp = null;
    }

    public function getFingerprint() : string
    {
        if (!$this->hasFingerprint()) {
            throw new RuntimeException("No fingerprints");
        }

        return $this->fingerPrint;
    }

    public function hasFingerprint() : bool
    {
        return !is_null($this->fingerPrint);
    }

    public function delete($filename) : void
    {
        if (!ssh2_sftp_unlink($this->sftp, $filename)) {
            throw new FailedToDelete("Failed to delete file");
        }
    }

    public function chmod($filename, $mode) : void
    {
        if (!ssh2_sftp_chmod($this->sftp, $filename, $mode)) {
            throw new FailedToChmod("Failed to change folder mode");
        }
    }

    public function symbolicLinkStat($path) : array
    {
        return ssh2_sftp_lstat($this->sftp, $path);
    }

    public function mkdir($dirname, $mode = 0744, $recursive = false) : void
    {
        if (!ssh2_sftp_mkdir($this->sftp, $dirname, $mode, $recursive)) {
            throw new FailedToMkdir("Failed to create directory");
        }
    }

    public function readlink($link) : string
    {
        return ssh2_sftp_readlink($this->sftp, $link);
    }

    public function realpath($filename) : string
    {
        return ssh2_sftp_realpath($this->sftp, $filename);
    }

    public function renameFromTo($from, $to) : void
    {
        if (!ssh2_sftp_rename($this->sftp, $from, $to)) {
            throw new FailedToRename("Failed to rename file");
        }
    }

    public function rmdir($dirname) : void
    {
        if (!ssh2_sftp_rmdir($this->sftp, $dirname)) {
            throw new FailedToRmDir("Fail to remove directory");
        }
    }

    public function stat($path) : array
    {
        return ssh2_sftp_stat($this->sftp, $path);
    }

    public function symlink($target, $link) : void
    {
        if (!ssh2_sftp_symlink($this->sftp, $target, $link)) {
            throw new FailedToSymlink("Fail to make symlink");
        }
    }

    public function copyLocalToRemote($local, $remote) : void
    {
        if (!$this->copyFile($local, $this->makeSsh2Link().$remote)) {
            throw new FailedToPutFile("Failed to put local file on remote server");
        }
    }

    private function copyFile($source, $target) : bool
    {
        $source_handler = fopen($source, 'r');
        $target_handler = fopen($target, 'w');

        $result = $writtenBytes = stream_copy_to_stream($source_handler, $target_handler);
        fclose($source_handler);
        fclose($target_handler);

        return $result;
    }

    private function makeSsh2Link() : string
    {
        $sftp = intval($this->sftp);

        return "ssh2.sftp://{$sftp}/";
    }

    public function copyRemoteToLocal($remote, $local) : void
    {
        if (!$this->copyFile($this->makeSsh2Link().$remote, $local)) {
            throw new FailedToGetFile("Failed to get remote file");
        }
    }

    public function isDir($path) : bool
    {
        return $this->isDir($this->makeSsh2Link().$path);
    }

    public function read($filename) : string
    {
        return file_get_contents($this->makeSsh2Link().$filename);
    }

}