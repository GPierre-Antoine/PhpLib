<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 25/07/18
 * Time: 14:13
 */

namespace PAG\Bridges\Javascript;


interface JavascriptConvertible
{
    public static function getClassName(): string;

    public static function getBaseclassName(): string;

    /** @return array|string[] */
    public static function getConvertibleMethods(): array;
}