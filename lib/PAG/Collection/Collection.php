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
        $this->arr = $array;
        $this->options = $options;
    }

    public static function explode($delimiter, $string, $options = []) : self
    {
        return static::makeCollection(explode($delimiter, $string), $options);
    }

    /**
     * @param       $array
     * @param array $options
     *
     * @return Collection
     */
    public static function makeCollection($array, $options) : self
    {
        return new Collection($array, $options);
    }

    public function duplicate() : self
    {
        return static::makeCollection($this->arr, $this->options);
    }

    public function fromRange($first, $last = null, $step = 1) : self
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

    public function values() : self
    {
        return static::makeCollection(array_values($this->arr), $this->options);
    }

    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->arr);
    }

    public function offsetUnset($offset) : void
    {
        unset($this->arr[$offset]);
    }

    public function serialize() : string
    {
        return serialize($this->arr);
    }

    public function unserialize($serialized) : void
    {
        $this->arr = unserialize($serialized);
    }

    public function jsonSerialize()
    {
        return $this->arr;
    }

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

    public function push($var) : self
    {
        array_push($this->arr, $var);

        return $this;
    }

    public function sort($callable = null) : self
    {
        if ($this->isAssociative()) {
            $sort = "asort";
        } else {
            $sort = "sort";
        }
        $sort = "u$sort";
        $sort($this->arr, $callable);

        return $this;
    }

    public function isAssociative() : bool
    {
        return $this->count() && ($this->keys()->arr !== range(0, $this->count() - 1));
    }

    public function count() : int
    {
        return count($this->arr);
    }

    public function keys() : self
    {
        return static::makeCollection(array_keys($this->arr), $this->options);
    }

    public function pop()
    {
        return array_pop($this->arr);
    }

    /**
     * @param $closure \Closure
     *
     * @return Collection
     */
    public function map($closure) : self
    {
        return static::makeCollection(array_map($closure, $this->arr), $this->options);
    }

    public function walk($closure, $userdata = null) : self
    {
        $array = $this->arr;
        array_walk($array, $closure, $userdata);

        return self::makeCollection($array, $this->options);
    }

    public function filter($closure) : self
    {
        return static::makeCollection(array_filter($this->arr, $closure), $this->options);
    }

    public function concat(Collection... $others) : self
    {
        $other = array_map(function (Collection $collection) {
            return $collection->arr;
        }, $others);

        return static::makeCollection(array_merge($this->arr, ...$other), $this->options);
    }

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new \RuntimeException('Unknown key : '.$offset);
        }

        return $this->arr[$offset];
    }

    public function offsetExists($offset) : bool
    {
        return $this->hasKey($offset);
    }

    public function hasKey($item) : bool
    {
        return isset($this->arr[$item]) && array_key_exists($item, $this->arr);
    }

    public function offsetSet($offset, $value) : void
    {
        if (!is_null($offset)) {
            $this->arr[$offset] = $value;
        } else {
            $this->arr[] = $value;
        }
    }

    public function hasValue($value) : bool
    {
        return in_array($value, $this->arr);
    }

    public function find($item, $strict = false)
    {
        return array_search($item, $this->arr, $strict);
    }

    public function flip() : self
    {
        return static::makeCollection(array_flip($this->arr), $this->options);
    }

    public function absorb($collection) : self
    {
        foreach ($collection as $item) {
            $this[] = $item;
        }

        return $this;
    }

    public function combine(Collection $other) : self
    {
        return static::makeCollection(array_combine($this->arr, $other->arr), $this->options);
    }

    public function intersect($collections) : self
    {
        $args = static::makeCollection(func_get_args(), $this->options);
        $args->unshift($this);

        return $args->intersectRecursive();
    }

    public function unshift($var) : self
    {
        array_unshift($this->arr, $var);

        return $this;
    }

    public function intersectRecursive() : self
    {
        $args = [];
        foreach ($this->arr as $collection) {
            if (is_array($collection)) {
                $args[] = $collection;
            } else {
                $args[] = $collection->arr;
            }
        }

        return static::makeCollection(call_user_func_array('array_intersect', $args), $this->options);
    }

    public function getArrayCopy() : array
    {
        return $this->arr;
    }

    public function reverse() : self
    {
        return static::makeCollection(array_reverse($this->arr), $this->options);
    }

    public function reduce($closure)
    {
        return array_reduce($this->arr, $closure);
    }

    public function __toString() : string
    {
        if ($this->isAssociative()) {
            $string = "";
            foreach ($this as $key => $value) {
                $string .= "$key => $value, ";
            }

            return "[".substr($string, 0, mb_strlen($string) - 2)."]";
        } else {
            return "[".$this->join(', ')."]";
        }
    }

    public function join($string) : string
    {
        return implode($string, $this->arr);
    }

    public function slice($offset, $length = null, $preserve_keys = false) : self
    {
        return static::makeCollection(array_slice($this->arr, $offset, $length, $preserve_keys), $this->options);
    }

    public final function downcast() : Collection
    {
        return new Collection($this->arr);
    }
}