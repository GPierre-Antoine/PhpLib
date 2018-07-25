<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 25/07/18
 * Time: 14:49
 */

namespace PAG\IO\Format;


interface TextFormat
{
    public function convert(TextFormatter $formatter, string $string): string;
}