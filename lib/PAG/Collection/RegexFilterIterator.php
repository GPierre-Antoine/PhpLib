<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 19/10/18
 * Time: 17:48
 */

namespace PAG\Collection;


use Iterator;

class RegexFilterIterator implements \Iterator
{
    /**
     * @var Iterator
     */
    private $innerIterator;
    private $pattern;

    public function __construct(Iterator $innerIterator, $pattern)
    {
        $this->innerIterator = $innerIterator;
        $this->pattern       = $pattern;
    }

    public function next()
    {
        $this->innerIterator->next();
    }

    public function valid()
    {
        while ($this->innerIterator->valid() && !preg_match($this->pattern, $this->current())) {
            $this->innerIterator->next();
        }
        return $this->innerIterator->valid();
    }

    public function current()
    {
        return $this->innerIterator->current();
    }

    public function key()
    {
        return $this->innerIterator->key();
    }

    public function rewind()
    {
        $this->innerIterator->rewind();
    }
}