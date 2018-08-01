<?php

namespace PAG\Connection;

class Ftp implements FileTransferConnection
{
    const MODE_PASSIVE = true;
    const MODE_ACTIVE  = false;
    private $connection;
    private $initial_directory;
    private $old_pwd;
    private $sys_type;

    public function __construct()
    {
        if (count(func_get_args()) === 3) {
            $this->connect(func_get_arg(0), func_get_arg(1), func_get_arg(2));
        }

    }

    public function connect($host, $port, AuthenticationModule $module)
    {
        $this->setConnection($module->visitFtp($this, $host, $port));
        $this->setMode(Ftp::MODE_PASSIVE);
    }

    protected function setConnection($connection)
    {
        $this->connection        = $connection;
        $this->initial_directory = $this->pwd();
        $this->sys_type          = ftp_systype($this->connection);
    }

    public function pwd()
    {
        return ftp_pwd($this->connection);
    }

    public function setMode($mode)
    {
        return ftp_pasv($this->connection, $mode);
    }

    public function mv($source, $destination)
    {
        return ftp_rename($this->connection, $source, $destination);
    }

    public function __destruct()
    {
        if (!is_null($this->connection)) {
            ftp_close($this->connection);
        }
    }

    public function ls()
    {
        $directory = count(func_get_args()) === 0
            ? $this->pwd()
            : func_get_args()[0];

        return ftp_nlist($this->connection, $directory);
    }

    public function exec($argument)
    {
        return ftp_exec($this->connection, $argument);
    }

    public function cd()
    {
        $directory = count(func_get_args()) === 0
            ? $this->initial_directory
            : func_get_args()[0];

        if ($directory === '-') {
            $directory = $this->old_pwd;
        }
        if (!$this->isDir($directory)) {
            return false;
        }
        $this->old_pwd = $this->pwd();

        return ftp_chdir($this->connection, $directory);
    }

    public function isDir($file)
    {
        if ($this->sys_type === 'Windows_NT') {
            $rawlist = ftp_rawlist($this->connection, $file);

            return strpos($rawlist[0], '<DIR>') !== false;
        }
        if ($this->sys_type === 'MACOS') {
            $filename   = basename($file);
            $foldername = dirname($file);
            if (strlen($filename) > 1) {
                $filename = substr($filename, 0, -1) . '*';
            }

            if (substr($foldername, -1) !== '/') {
                $foldername .= '/';
            }
            $raw_list = ftp_rawlist($this->connection, $foldername . $filename);
            foreach ($raw_list as $value) {
                preg_match('/^.{10}\s+[a-zA-Z0-9]+\s+[0-9]+\s[a-zA-Z]+\s+[0-9]{1,2}\s[0-9]{1,2}:[0-9]{2}\s(.+)/',
                    $value,
                    $capture);
                if (count($capture) < 2 || $capture[1] !== basename($file)) {
                    continue;
                }
                return $value[0] === 'd';

            }
            return false;
        }

        $filename   = basename($file);
        $foldername = dirname($file);
        if (strlen($filename) > 1) {
            $filename = substr($filename, 0, -1) . '*';
        }

        if (substr($foldername, -1) !== '/') {
            $foldername .= '/';
        }
        $raw_list = ftp_rawlist($this->connection, $foldername . $filename);
        foreach ($raw_list as $value) {
            preg_match('/.{10}\s+[0-9]+\s+(?:[a-zA-Z0-9]+\s+){2}[0-9]+\s[A-Z][a-z]{2}\s[0-9]{2}\s[0-9]{2}:[0-9]{2}\s(.+)/',
                $value,
                $capture);
            if (count($capture) < 2 || $capture[1] !== basename($file)) {
                continue;
            }
            return $value[0] === 'd';

        }
        return false;
    }

    public function rm($path_to_remote_file)
    {
        return ftp_delete($this->connection, $path_to_remote_file);
    }

    public function mkdir($directory)
    {
        return ftp_mkdir($this->connection, $directory);
    }

    public function getSySType()
    {
        return $this->sys_type;
    }

    public function copyLocalToRemote($local, $remote)
    {
        if (self::checkMimeTypeIsBinary($local)) {
            $this->put_binary($local, $remote);
        }
        else {
            $this->put_ascii($local, $remote);
        }
    }

    public static function checkMimeTypeIsBinary($filename): bool
    {
        $mime = self::getMime($filename);
        return !preg_match('/text/', $mime);
    }

    private static function getMime($filename): string
    {
        $finfo = finfo_open(FILEINFO_MIME);
        return finfo_file($finfo, $filename);
    }

    public function put_binary($local_file, $path_to_remote_file)
    {
        return $this->put($local_file, $path_to_remote_file, FTP_BINARY);
    }

    public function put($local_file, $path_to_remote_file, $mode = FTP_BINARY)
    {
        return ftp_put($this->connection, $path_to_remote_file, $local_file, $mode);
    }

    public function put_ascii($local_file, $path_to_remote_file)
    {
        return $this->put($local_file, $path_to_remote_file, FTP_ASCII);
    }

    public function copyRemoteToLocal($remote, $local)
    {
        if (self::checkMimeTypeIsBinary($remote)) {
            $this->get_binary($remote, $local);
        }
        else {
            $this->get_ascii($remote, $local);
        }
    }

    public function get_binary($path_to_remote_file, $local_file)
    {
        return $this->get($path_to_remote_file, $local_file, FTP_BINARY);
    }

    public function get($path_to_remote_file, $local_file, $mode = FTP_BINARY)
    {
        return ftp_get($this->connection, $local_file, $path_to_remote_file, $mode);
    }

    public function get_ascii($path_to_remote_file, $local_file)
    {
        return $this->get($path_to_remote_file, $local_file, FTP_ASCII);
    }
}