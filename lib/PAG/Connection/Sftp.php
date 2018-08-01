<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 01/08/18
 * Time: 10:18
 */

namespace PAG\Connection;


use RuntimeException;

class Sftp implements Ssh2, FileTransferConnection
{
    const ERROR_MKDIR   = 1;
    const ERROR_RM      = 2;
    const ERROR_RMDIR   = 3;
    const ERROR_MV      = 4;
    const ERROR_SYMLINK = 5;
    const ERROR_COPY    = 6;

    private $connection;
    private $sftp;
    private $fingerPrint;

    public function connect($host, $port, AuthenticationModule $module, $fingerprint = null)
    {
        $this->fingerPrint = $fingerprint;
        $this->setConnection($module->visitSsh2($this, $host, $port));
    }

    private function setConnection($connection)
    {
        $this->connection = $connection;
        $this->buildSftpConnection();
    }

    private function buildSftpConnection()
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

    public function disconnect()
    {
        if (!$this->connection) {
            return;
        }
        $this->connection = null;
        $this->sftp       = null;
    }

    public function getFingerprint()
    {
        if (!$this->hasFingerprint()) {
            throw new RuntimeException("No fingerprints");
        }
        return $this->fingerPrint;
    }

    public function hasFingerprint()
    {
        return !is_null($this->fingerPrint);
    }

    public function delete($filename)
    {
        if (!ssh2_sftp_unlink($this->sftp, $filename)) {
            $this->error("Failed to delete file", self::ERROR_RM);
        }
    }

    public function error($message, $code)
    {
        throw new RuntimeException($message, $code);
    }

    public function chmod($filename, $mode)
    {
        ssh2_sftp_chmod($this->sftp, $filename, $mode);
    }

    public function symbolicLinkStat($path)
    {
        ssh2_sftp_lstat($this->sftp, $path);
    }

    public function mkdir($dirname, $mode = 0744, $recursive = false)
    {
        if (!ssh2_sftp_mkdir($this->sftp, $dirname, $mode, $recursive)) {
            $this->error("Failed to create directory", self::ERROR_MKDIR);
        }
    }

    public function readlink($link)
    {
        return ssh2_sftp_readlink($this->sftp, $link);
    }

    public function realpath($filename)
    {
        return ssh2_sftp_realpath($this->sftp, $filename);
    }

    public function renameFromTo($from, $to)
    {
        if (!ssh2_sftp_rename($this->sftp, $from, $to)) {
            $this->error("Failed to rename file", self::ERROR_MV);
        }
    }

    public function rmdir($dirname)
    {
        if (!ssh2_sftp_rmdir($this->sftp, $dirname)) {
            $this->error("Fail to remove directory", self::ERROR_RMDIR);
        }
    }

    public function stat($path)
    {
        return ssh2_sftp_stat($this->sftp, $path);
    }

    public function symlink($target, $link)
    {
        if (!ssh2_sftp_symlink($this->sftp, $target, $link)) {
            $this->error("Fail to make symlink", self::ERROR_SYMLINK);
        }
    }

    public function copyLocalToRemote($local, $remote)
    {

        $this->copyFile($local, $this->makeSsh2Link() . $remote);
    }
    public function copyRemoteToLocal($remote, $local)
    {
        $this->copyFile($this->makeSsh2Link() . $remote, $local);
    }

    private function copyFile($source, $target)
    {
        $source_handler = fopen($source, 'r');
        $target_handler = fopen($target, 'w');

        $result = $writtenBytes = stream_copy_to_stream($source_handler, $target_handler);
        fclose($source_handler);
        fclose($target_handler);
        if (!$result) {
            $this->error("Failed to copy file", self::ERROR_COPY);
        }
    }

    private function makeSsh2Link()
    {
        $sftp = intval($this->sftp);
        return "ssh2.sftp://{$sftp}/";
    }

    public function isDir($path)
    {
        return $this->isDir($this->makeSsh2Link() . $path);
    }

    public function read($filename)
    {
        return file_get_contents($this->makeSsh2Link() . $filename);
    }

}