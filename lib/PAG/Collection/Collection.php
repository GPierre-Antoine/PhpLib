<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 24/07/2018
 * Time: 23:37
 */

namespace PAG\Collection;


class Collection implements \IteratorAggregate, \ArrayAccess, \Countable,
                            \Serializable, \JsonSerializable
{
    protected $arr;
    protected $options;

    public function __construct(array $array = [], $options = [])
    {
        $this->arr     = $array;
        $this->options = $options;
    }

    public static function explode($delimiter, $string, $options = []): self
    {
        return static::makeCollection(explode($delimiter, $string), $options);
    }

    /**
     * @param       $array
     * @param array $options
     * @return Collection
     */
    public static function makeCollection($array, $options): self
    {
        return new Collection($array, $options);
    }

    public function duplicate(): self
    {
        return static::makeCollection($this->arr, $this->options);
    }

    public function fromRange($first, $last = null, $step = 1)
    {
        $array = [];
        if (is_null($last)) {
            $last = count($this);
        }
        $values = $this->values();
        for (; $first < count($values) && $first < $last; $first += $step) {
            $array[] = $values[$first];
        }

        return static::makeCollection($array, $this->options);
    }

    public function values()
    {
        return static::makeCollection(array_values($this->arr), $this->options);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->arr);
    }

    public function offsetUnset($offset)
    {
        unset($this->arr[$offset]);
    }

    public function serialize()
    {
        return serialize($this->arr);
    }

    public function unserialize($serialized)
    {
        $this->arr = unserialize($serialized);
    }

    public function jsonSerialize()
    {
        return $this->arr;
    }

    /**
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->arr);
    }

    public function first()
    {
        foreach ($this->arr as &$value) {
            return $value;
        }

        return null;
    }

    public function push($var)
    {
        array_push($this->arr, $var);

        return $this;
    }

    public function sort($callable = null)
    {
        if ($this->isAssociative()) {
            $sort = "asort";
        }
        else {
            $sort = "sort";
        }
        $sort = "u$sort";
        $sort($this->arr, $callable);
    }

    public function isAssociative()
    {
        return $this->count() && ($this->keys()->arr !== range(0, $this->count() - 1));
    }

    public function count()
    {
        return count($this->arr);
    }

    public function keys()
    {
        return static::makeCollection(array_keys($this->arr), $this->options);
    }

    public function pop()
    {
        return array_pop($this->arr);
    }

    /**
     * @param $closure \Closure
     * @return Collection
     */
    public function map($closure)
    {
        return static::makeCollection(array_map($closure, $this->arr), $this->options);
    }

    public function walk($closure, $userdata = null)
    {
        $array = $this->arr;
        array_walk($array, $closure, $userdata);
        return self::makeCollection($array, $this->options);
    }

    /**
     * @param $closure \Closure
     * @return Collection
     */
    public function filter($closure)
    {
        return static::makeCollection(array_filter($this->arr, $closure), $this->options);
    }

    /**
     * @param Collection... $other
     * @return Collection
     */
    public function concat(Collection $other)
    {
        return static::makeCollection(array_merge($this->arr, $other->arr), $this->options);
    }

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new \RuntimeException('Unknown key : ' . $offset);
        }

        return $this->arr[$offset];
    }

    public function offsetExists($offset)
    {
        return $this->hasKey($offset);
    }

    public function hasKey($item)
    {
        return isset($this->arr[$item]) && array_key_exists($item, $this->arr);
    }

    public function offsetSet($offset, $value)
    {
        if (!is_null($offset)) {
            $this->arr[$offset] = $value;
        }
        else {
            $this->arr[] = $value;
        }
    }

    public function hasValue($value)
    {
        return in_array($value, $this->arr);
    }

    public function find($item, $strict = false)
    {
        return array_search($item, $this->arr, $strict);
    }

    public function flip()
    {
        return static::makeCollection(array_flip($this->arr), $this->options);
    }

    public function absorb($collection)
    {
        foreach ($collection as $item) {
            $this[] = $item;
        }
    }

    public function combine(Collection $other)
    {
        return static::makeCollection(array_combine($this->arr, $other->arr), $this->options);
    }

    public function intersect($collections): self
    {
        $args = static::makeCollection(func_get_args(), $this->options);
        $args->unshift($this);

        return $args->intersectRecursive();
    }

    public function unshift($var)
    {
        array_unshift($this->arr, $var);

        return $this;
    }

    public function intersectRecursive()
    {
        $args = [];
        foreach ($this->arr as $collection) {
            if (is_array($collection)) {
                $args[] = $collection;
            }
            else {
                $args[] = $collection->arr;
            }
        }

        return static::makeCollection(call_user_func_array('array_intersect', $args), $this->options);
    }

    public function getArrayCopy()
    {
        return $this->arr;
    }

    public function reverse()
    {
        return static::makeCollection(array_reverse($this->arr), $this->options);
    }

    public function reduce($closure)
    {
        return array_reduce($this->arr, $closure);
    }

    public function __toString()
    {
        if ($this->isAssociative()) {
            $string = "";
            foreach ($this as $key => $value) {
                $string .= "$key => $value, ";
            }
            return "[" . substr($string, 0, mb_strlen($string) - 2) . "]";
        }
        else {
            return "[" . $this->join(', ') . "]";
        }
    }

    public function join($string)
    {
        return implode($string, $this->arr);
    }

    public function slice($offset, $length = null, $preserve_keys = false)
    {
        return static::makeCollection(array_slice($this->arr, $offset, $length, $preserve_keys), $this->options);
    }

    public final function downcast()
    {
        return new Collection($this->arr);
    }
}