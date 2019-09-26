<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 15:56
 */

namespace PAG\Stream;


use php_user_filter;

class StreamManager implements StreamFilterVisitor
{
    private $streamFilterName;
    private $closure;

    public function __construct($streamFilterName, $closure)
    {
        $this->streamFilterName = $streamFilterName;
        $this->closure          = $closure;
    }

    public static function register($name, php_user_filter $filter)
    {
        stream_filter_register($name, get_class($filter));
    }

    public static function append(StreamFilter $filter, $name)
    {
        $filter->accept(new StreamManager($name, "stream_filter_append"));
    }

    public static function prepend(StreamFilter $filter, $name)
    {
        $filter->accept(new StreamManager($name, "stream_filter_prepend"));
    }

    public function visitReader(ReadStreamFilter $readStreamFilter)
    {
        $this($readStreamFilter, STREAM_FILTER_READ);
    }

    public function visitWrite(WriteStreamFilter $writeStreamFilter)
    {
        $this($writeStreamFilter, STREAM_FILTER_WRITE);
    }

    public function visitReadWrite(ReadWriteStreamFilter $readWriteStreamFilter)
    {
        $this($readWriteStreamFilter, STREAM_FILTER_ALL);
    }

    public function __invoke($stream, $flags)
    {
        call_user_func($this->closure, $stream, $this->streamFilterName, $flags);
    }
}