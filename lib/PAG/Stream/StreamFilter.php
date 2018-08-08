<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 15:58
 */

namespace PAG\Stream;


interface StreamFilter
{
    public function accept(StreamFilterVisitor $visitor):void;
}