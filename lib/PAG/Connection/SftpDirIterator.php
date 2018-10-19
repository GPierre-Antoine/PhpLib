<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 19/10/18
 * Time: 17:05
 */

namespace PAG\Connection;


class SftpDirIterator implements \Iterator
{
    private $connectionString;
    private $opened;
    private $resource;
    private $key = 0;
    private $current;
    private $path;


    public function __construct($connectionString, $path)
    {
        $this->connectionString = $connectionString;
        $this->path             = $path;
    }

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->key;
    }

    public function valid()
    {
        return $this->current != false;
    }

    public function rewind()
    {
        $this->close();
        $this->open();
        $this->next();
    }

    private function close(): void
    {
        if ($this->opened) {
            closedir($this->resource);
            $this->opened = false;
        }
    }

    private function open(): void
    {
        $this->resource = opendir($this->connectionString . $this->path);
        if (!$this->resource) {
            throw new \RuntimeException("No such directory {$this->path}");
        }
        $this->opened = true;
    }

    public function next()
    {
        $this->current = readdir($this->resource);
        $this->key     += 1;
    }

    public function __destruct()
    {
        $this->close();
    }
}