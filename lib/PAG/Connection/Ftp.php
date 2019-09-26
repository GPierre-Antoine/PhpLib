<?php

namespace PAG\Connection;

use PAG\Connection\Exception\FailedToChangeMode;
use PAG\Connection\Exception\FailedToDelete;
use PAG\Connection\Exception\FailedToGetFile;
use PAG\Connection\Exception\FailedToMkdir;
use PAG\Connection\Exception\FailedToPutFile;

class Ftp implements RemoteFileTransferTool, Connection
{

    const MODE_PASSIVE = true;
    const MODE_ACTIVE = false;
    const MATCH_UNIX_LS = '/^.{10}\s+[a-zA-Z0-9]+\s+[0-9]+\s[a-zA-Z]+\s+[0-9]{1,2}\s[0-9]{1,2}:[0-9]{2}\s(.+)/';
    const MATCH_LINUX_LS = '/.{10}\s+[0-9]+\s+(?:[a-zA-Z0-9]+\s+){2}[0-9]+\s[A-Z][a-z]{2}\s[0-9]{2}\s[0-9]{2}:[0-9]{2}\s(.+)/';

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

    public function connect($host, $port, AuthenticationModule $module): void
    {
        $this->setConnection($module->visitFtp($this, $host, $port));
        $this->setModePassive();
    }

    protected function setConnection($connection): void
    {
        $this->connection = $connection;
        $this->initial_directory = $this->pwd();
        $this->sys_type = ftp_systype($this->connection);
    }

    public function pwd(): string
    {
        return ftp_pwd($this->connection);
    }

    public function setModePassive(): void
    {
        $this->setMode(Ftp::MODE_PASSIVE);
    }

    private function setMode($mode): void
    {
        if (!ftp_pasv($this->connection, $mode)) {
            throw new FailedToChangeMode("Could not change mode");
        }
    }

    public function setModeActive(): void
    {
        $this->setMode(Ftp::MODE_ACTIVE);
    }

    public function mv($source, $destination)
    {
        return ftp_rename($this->connection, $source, $destination);
    }

    public function __destruct()
    {
        if (is_null($this->connection)) {
            return;
        }
        ftp_close($this->connection);
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
            /** @noinspection HtmlDeprecatedTag */
            return strpos($rawlist[0], '<DIR>') !== false;
        }
        if ($this->sys_type === 'MACOS') {
            $raw_list = $this->getRawList($file);

            foreach ($raw_list as $value) {
                preg_match(self::MATCH_UNIX_LS, $value, $capture);
                if (count($capture) < 2 || $capture[1] !== basename($file)) {
                    continue;
                }

                return $value[0] === 'd';

            }

            return false;
        }

        $raw_list = $this->getRawList($file);
        foreach ($raw_list as $value) {
            preg_match(self::MATCH_LINUX_LS, $value, $capture);
            if (count($capture) < 2 || $capture[1] !== basename($file)) {
                continue;
            }

            return $value[0] === 'd';

        }

        return false;
    }

    /**
     * @param $file
     * @return array
     */
    protected function getRawList($file): array
    {
        $filename = basename($file);
        $foldername = dirname($file);
        if (strlen($filename) > 1) {
            $filename = substr($filename, 0, -1) . '*';
        }

        if (substr($foldername, -1) !== '/') {
            $foldername .= '/';
        }
        $raw_list = ftp_rawlist($this->connection, $foldername . $filename);
        return $raw_list;
    }

    public function rm($path_to_remote_file)
    {
        if (!ftp_delete($this->connection, $path_to_remote_file)) {
            throw new FailedToDelete("Failed to delete file");
        }
    }

    public function mkdir($directory)
    {
        if (!ftp_mkdir($this->connection, $directory)) {
            throw new FailedToMkdir("Failed to create directory");
        }
    }

    public function getSySType()
    {
        return $this->sys_type;
    }

    public function copyLocalToRemote($local, $remote): void
    {
        if (self::inferMimeTypeIsBinary($local)) {
            $this->put_binary($local, $remote);
        } else {
            $this->put_ascii($local, $remote);
        }
    }

    public static function inferMimeTypeIsBinary($filename): bool
    {
        $mime = self::getMime($filename);

        return !preg_match('/text/', $mime);
    }

    private static function getMime($filename): string
    {
        $finfo = finfo_open(FILEINFO_MIME);

        return finfo_file($finfo, $filename);
    }

    public function put_binary($local_file, $path_to_remote_file): void
    {
        $this->put($local_file, $path_to_remote_file, FTP_BINARY);
    }

    public function put($local_file, $path_to_remote_file, $mode = FTP_BINARY): void
    {
        if (!ftp_put($this->connection, $path_to_remote_file, $local_file, $mode)) {
            throw new FailedToPutFile("Failed to put local file");
        }
    }

    public function put_ascii($local_file, $path_to_remote_file): void
    {
        $this->put($local_file, $path_to_remote_file, FTP_ASCII);
    }

    public function copyRemoteToLocal($remote, $local): void
    {
        if (self::inferMimeTypeIsBinary($remote)) {
            $this->get_binary($remote, $local);
        } else {
            $this->get_ascii($remote, $local);
        }
    }

    public function get_binary($path_to_remote_file, $local_file): void
    {
        $this->get($path_to_remote_file, $local_file, FTP_BINARY);
    }

    public function get($path_to_remote_file, $local_file, $mode = FTP_BINARY): void
    {
        if (!ftp_get($this->connection, $local_file, $path_to_remote_file, $mode)) {
            throw new FailedToGetFile("Failed to get remote file");
        }
    }

    public function get_ascii($path_to_remote_file, $local_file): void
    {
        $this->get($path_to_remote_file, $local_file, FTP_ASCII);
    }
}

