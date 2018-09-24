<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 24/09/18
 * Time: 15:16
 */

namespace PAG\Collection;


class ArrayView implements \ArrayAccess
{
    private $array;
    private $callable;

    public function __construct(&$array, $callable)
    {
        $this->array    = &$array;
        $this->callable = $callable;
    }

    public function offsetGet($offset)
    {
        return $this->view($this->array[$offset]);
    }

    public function view($string)
    {
        return call_user_func($this->callable, $string);
    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        $value = $this->view($value);
        if (is_null($offset)) {
            $this->array[] = $value;
        }
        else {
            $this->array[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }
}