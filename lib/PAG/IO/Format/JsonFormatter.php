<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 28/08/18
 * Time: 14:07
 */

namespace PAG\IO\Format;


class JsonFormatter implements ArrayFormatter
{
    public function generateOutputFromArray(array $array):string
    {
        return json_encode($array);
    }
}