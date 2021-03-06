<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 25/07/18
 * Time: 12:25
 */

namespace PAG\Collection;


class AutoHashCollection extends Collection
{
    const CLOSURE = 'closure';

    public function __construct($closure, $array = [], $options = [])
    {
        $options[static::CLOSURE] = $closure;
        parent::__construct([], $options);
        foreach ($array as $value) {
            $this[] = $value;
        }
    }

    /**
     * @param $array
     * @param array $options
     * @return static
     */
    public static function makeCollection(array $array, array $options): Collection
    {
        return new AutoHashCollection($options[static::CLOSURE], $array, $options);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $offset = call_user_func($this->options[static::CLOSURE], $value);
        }
        parent::offsetSet($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function slice($offset, $length = null, $preserve_keys = true): Collection
    {
        return parent::slice($offset, $length, $preserve_keys);
    }
}