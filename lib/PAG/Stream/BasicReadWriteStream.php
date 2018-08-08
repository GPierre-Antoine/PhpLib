<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 16:23
 */

namespace PAG\Stream;


use PAG\Traits\StreamFilterReaderWriterTrait;

abstract class BasicReadWriteStream extends \php_user_filter implements ReadWriteStreamFilter
{
    use StreamFilterReaderWriterTrait;
}