<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 25/07/18
 * Time: 14:22
 */

namespace PAG\IO\Format;


class ConsoleTextFormatter implements TextFormat
{
    public function convert(TextFormatter $formatter, string $string): string
    {
        return $string;
    }
}