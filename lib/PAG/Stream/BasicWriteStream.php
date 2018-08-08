<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 16:23
 */

namespace PAG\Stream;


use PAG\Traits\StreamFilterWriterTrait;

abstract class BasicWriteStream extends \php_user_filter implements WriteStreamFilter
{
    use StreamFilterWriterTrait;
}