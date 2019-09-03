<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 19/10/18
 * Time: 17:48
 */

namespace PAG\Collection;


use Iterator;
use PAG\Collection\Filter\Filter;

class FilterIterator implements Iterator
{
    private $innerIterator;
    private $filter;

    public function __construct(Iterator $innerIterator, Filter $pattern)
    {
        $this->innerIterator = $innerIterator;
        $this->filter        = $pattern;
    }

    public function next()
    {
        $this->innerIterator->next();
    }

    public function valid()
    {
        while ($this->innerIterator->valid() && $this->filter->filter($this->current())) {
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