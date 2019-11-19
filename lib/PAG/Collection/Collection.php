<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 24/07/2018
 * Time: 23:37
 */

namespace PAG\Collection;


use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Serializable;

class Collection implements IteratorAggregate, ArrayAccess, Countable,
                            Serializable, JsonSerializable
{
    protected $arr;
    protected $options;

    public function __construct(array $array = [], array $options = [])
    {
        $this->arr     = $array;
        $this->options = $options;
    }

    public static function explode(string $delimiter, string $string, array $options = []): self
    {
        return static::makeCollection(explode($delimiter, $string), $options);
    }

    public static function makeCollection(array $array, array $options): self
    {
        return new Collection($array, $options);
    }

    public function duplicate(): self
    {
        return static::makeCollection($this->arr, $this->options);
    }

    public function fromRange(int $first, int $last = null, int $step = 1): self
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

    public function values(): self
    {
        return static::makeCollection(array_values($this->arr), $this->options);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->arr);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->arr[$offset]);
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function serialize(): string
    {
        return serialize($this->arr);
    }

    /**
     * @inheritDoc
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        $this->arr = unserialize((string)$serialized);
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function jsonSerialize(): array
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

    /**
     * @return mixed
     * @throws CollectionException
     */
    public function first()
    {
        foreach ($this->arr as &$value) {
            return $value;
        }

        throw new CollectionException("Collection empty");
    }

    /**
     * @param $var
     * @return static
     */
    public function push($var): self
    {
        array_push($this->arr, $var);

        return $this;
    }

    /**
     * @param callable $closure
     * @return static
     */
    public function sort(callable $closure = null): self
    {
        $sort = "sort";
        if ($this->isAssociative()) {
            $sort = "a$sort";
        }
        $collection = self::makeCollection($this->arr, $this->options);
        if ($closure) {
            $sort = "u$sort";
            $sort($collection->arr, $closure);
        } else {
            $sort($collection->arr);
        }

        return $collection;
    }

    public function isAssociative(): bool
    {
        return $this->count() && ($this->keys()->arr !== range(0, $this->count() - 1));
    }

    public function count(): int
    {
        return count($this->arr);
    }

    public function keys(): self
    {
        return static::makeCollection(array_keys($this->arr), $this->options);
    }

    /**
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->arr);
    }

    public function map(callable $closure): self
    {
        return static::makeCollection(array_map($closure, $this->arr), $this->options);
    }

    /**
     * @param callable $closure
     * @param mixed $userdata
     * @return static
     */
    public function walk(callable $closure, $userdata = null): self
    {
        $array = $this->arr;
        array_walk($array, $closure, $userdata);

        return static::makeCollection($array, $this->options);
    }

    public function filter(callable $closure): self
    {
        return static::makeCollection(array_filter($this->arr, $closure), $this->options);
    }

    public function concat(Collection...$others): self
    {
        $other = array_map(
            function (Collection $collection) {
                return $collection->arr;
            },
            $others
        );

        return static::makeCollection(array_merge($this->arr, ...$other), $this->options);
    }

    /**
     * @param mixed $offset
     * @return mixed
     * @throws CollectionException
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new CollectionException('Unknown key : ' . $offset);
        }

        return $this->arr[$offset];
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->hasKey($offset);
    }

    /**
     * @param mixed $item
     * @return bool
     */
    public function hasKey($item): bool
    {
        return isset($this->arr[$item]) && array_key_exists($item, $this->arr);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        if (!is_null($offset)) {
            $this->arr[$offset] = $value;
        } else {
            $this->arr[] = $value;
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function hasValue($value): bool
    {
        return in_array($value, $this->arr);
    }

    /**
     * @param mixed $item
     * @param bool $strict
     * @return false|int|string
     */
    public function find($item, $strict = false)
    {
        return array_search($item, $this->arr, $strict);
    }

    public function flip(): self
    {
        return static::makeCollection(array_flip($this->arr), $this->options);
    }


    public function absorb(iterable $collection): self
    {
        foreach ($collection as $item) {
            $this[] = $item;
        }

        return $this;
    }

    public function combine(Collection $other): self
    {
        return static::makeCollection(array_combine($this->arr, $other->arr), $this->options);
    }

    public function intersect($collections): self
    {
        $args = static::makeCollection(func_get_args(), $this->options);
        $args->unshift($this);

        return $args->intersectRecursive();
    }

    public function unshift($var): self
    {
        array_unshift($this->arr, $var);

        return $this;
    }

    public function intersectRecursive(): self
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

    public function getArrayCopy(): array
    {
        return $this->arr;
    }

    public function reverse(): self
    {
        return static::makeCollection(array_reverse($this->arr), $this->options);
    }

    /**
     * @param callable $closure
     * @return mixed
     */
    public function reduce(callable $closure)
    {
        return array_reduce($this->arr, $closure);
    }

    public function __toString(): string
    {
        if ($this->isAssociative()) {
            $string = "";
            foreach ($this as $key => $value) {
                $string .= "$key => $value, ";
            }

            return "[" . substr($string, 0, mb_strlen($string) - 2) . "]";
        } else {
            return "[" . $this->join(', ') . "]";
        }
    }

    public function join(string $string): string
    {
        return implode($string, $this->arr);
    }

    /**
     * @param int $offset
     * @param int $length
     * @param bool $preserve_keys
     * @return static
     */
    public function slice($offset, $length = null, $preserve_keys = false): self
    {
        return static::makeCollection(array_slice($this->arr, $offset, $length, $preserve_keys), $this->options);
    }

    public final function downcast(): Collection
    {
        return new Collection($this->arr);
    }
}