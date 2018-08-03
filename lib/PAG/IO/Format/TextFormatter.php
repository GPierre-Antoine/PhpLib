<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 25/07/18
 * Time: 14:46
 */

namespace PAG\IO\Format;

final class TextFormatter
{
    const BOLD          = "\e[1m";
    const ITALIC        = "\e[3m";
    const UNDERLINE     = "\e[4m";
    const STRIKETHROUGH = "\e[9m";
    const RESET         = "\e[0m";

    public function bold($string)
    {
        return $this->effect(static::BOLD, $string);
    }

    public function effect($tag, $string)
    {
        return $tag . $string . static::RESET;
    }

    public function underline($string)
    {
        return $this->effect(static::UNDERLINE, $string);
    }

    public function italic($string)
    {
        return $this->effect(static::ITALIC, $string);
    }

    public function strikethrough($string)
    {
        return $this->effect(static::STRIKETHROUGH, $string);
    }

    public function convert(TextFormat $format, string $string): string
    {
        return $format->convert($this, $string);
    }
}