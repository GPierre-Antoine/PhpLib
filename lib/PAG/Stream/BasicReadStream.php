<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 16:23
 */

namespace PAG\Stream;


use PAG\Traits\StreamFilterReaderTrait;

abstract class BasicReadStream implements ReadStreamFilter
{
    use StreamFilterReaderTrait;
}