<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 08/08/18
 * Time: 14:56
 */

namespace PAG\Executor;


use Exception;
use PAG\Traits\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use RuntimeException;

class Executor implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __invoke(...$args)
    {
        try {
            if (!count($args)) {
                throw new RuntimeException("Nothing to do");
            }
            $arg = array_shift($args);
            $this->makeExecutable($arg, $args)->run();

        }
        catch (Exception $exception) {
            $this->getLogger()->error($exception->getMessage());
        }
    }

    private function makeExecutable($name, $options): Executable
    {
        $classArray = [
            "csvize" => "PAG\Executor\MakeIntoCsv",
        ];
        if (!array_key_exists($name, $classArray)) {
            throw new RuntimeException("No such thing $name");
        }
        $className = $classArray[$name];
        return new $className(...$options);
    }
}