<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 25/07/18
 * Time: 14:22
 */

namespace PAG\IO\Text;


class HtmlTextFormatter implements TextFormat
{
    public function convert(TextFormatter $formatter, string $string): string
    {
        $reset      = preg_quote($formatter::RESET);
        $new_string = $string;
        foreach ([
                     $formatter::UNDERLINE     => 'u',
                     $formatter::STRIKETHROUGH => 's',
                     $formatter::BOLD          => 'b',
                     $formatter::ITALIC        => 'i',
                 ] as $key => $value) {
            $key        = preg_quote($key);
            $new_string = preg_replace("#$key(.*?)$reset#", "<$value>$1</$value>", $string);
        }
        return $new_string;

    }
}