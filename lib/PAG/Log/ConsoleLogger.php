<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 15:08
 */

namespace PAG\Log;


use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;

class ConsoleLogger implements LoggerInterface
{
    use LoggerTrait;

    private static function interpolate(string $message, iterable $context)
    {
        foreach ($context as $key => $value) {
            $message = str_replace("${key}", $value, $message);
        }
        return $message;
    }

    public function log($level, $message, array $context = [])
    {
        $string = str_pad($level, 12, ' ', STR_PAD_RIGHT) . ": " . self::interpolate($message, $context) . PHP_EOL;

        $stream = $this->computeStream($level);
        fwrite($stream, $string);
    }

    private function computeStream($level)
    {
        if ($this->isBenign($level)) {
            $stream = STDOUT;
        } else {
            $stream = STDERR;
        }
        return $stream;
    }

    private function isBenign($level): bool
    {
        return in_array($level,
            [LogLevel::WARNING, LogLevel::ALERT, Loglevel::DEBUG, LogLevel::INFO, LogLevel::NOTICE]);
    }
}