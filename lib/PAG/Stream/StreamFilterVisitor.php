<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 15:58
 */

namespace PAG\Stream;


interface StreamFilterVisitor
{
    public function visitReader(ReadStreamFilter $readStreamFilter);

    public function visitWrite(WriteStreamFilter $writeStreamFilter);

    public function visitReadWrite(ReadWriteStreamFilter $readWriteStreamFilter);
}