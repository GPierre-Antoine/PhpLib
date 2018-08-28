<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 28/08/18
 * Time: 14:06
 */

namespace PAG\IO\Format;


interface ArrayFormatter
{
    public function generateOutputFromArray(array $array):string;
}