<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 16:16
 */

namespace PAG\Traits;


use PAG\Stream\StreamFilterVisitor;

trait StreamFilterReaderTrait
{
    public function accept(StreamFilterVisitor $visitor): void
    {
        $visitor->visitReader($this);
    }
}