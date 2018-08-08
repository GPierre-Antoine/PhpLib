<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 16:16
 */

namespace PAG\Traits;


use PAG\Stream\StreamFilterVisitor;

trait StreamFilterWriterTrait
{
    public function accept(StreamFilterVisitor $visitor)
    {
        return $visitor->visitWrite($this);
    }
}