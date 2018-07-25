<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 23/07/2018
 * Time: 19:41
 */

namespace PAG\Bridges\Javascript;


interface JavascriptConverter
{
    public static function convert(JavascriptConvertible $javascriptConvertible): string;
}