<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 25/07/18
 * Time: 14:46
 */

namespace PAG\IO\Format;

abstract class TextFormatter
{
    const BOLD          = "\e[1m";
    const ITALIC        = "\e[3m";
    const UNDERLINE     = "\e[4m";
    const STRIKETHROUGH = "\e[9m";
    const RESET         = "\e[0m";

    public final function bold($string)
    {
        return $this->effect(static::BOLD, $string);
    }

    public final function effect($tag, $string)
    {
        return $tag . $string . static::RESET;
    }

    public final function underline($string)
    {
        return $this->effect(static::UNDERLINE, $string);
    }

    public final function italic($string)
    {
        return $this->effect(static::ITALIC, $string);
    }

    public final function strikethrough($string)
    {
        return $this->effect(static::STRIKETHROUGH, $string);
    }

    public final function convert(string $string, TextFormat $format): string
    {
        return $format->convert($this, $string);
    }
}