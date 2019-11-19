<?php


namespace PAG\Collection;


use iterable;
use Iterator;
use ArrayIterator;
use IteratorIterator;

class MultiIterator implements Iterator
{

    /**
     * @var Iterator[]
     */
    private array $collections;

    private int $collectionIndex = 0;

    public function __construct(iterable ...$collections)
    {
        $this->collections = array_map(
            function ($collection) {
                if (is_array($collection)) {
                    return new ArrayIterator($collection);
                }
                return new IteratorIterator($collection);
            },
            $collections
        );
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->currentCollection()->current();
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->collections[$this->collectionIndex]->next();
        $this->cycleCollection();
        for ($i = 0; $i < (count($this->collections) - 1) && !$this->currentCollection()->valid(); ++$i) {
            $this->cycleCollection();
        }
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->currentCollection()->key();
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        for ($i = 0; $i < count($this->collections); ++$i) {
            if ($this->collections[$i]->valid()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->collectionIndex = 0;
        foreach ($this->collections as $key => $value) {
            $value->rewind();
        }
    }

    private function cycleCollection(): void
    {
        $this->collectionIndex += 1;
        if (count($this->collections) === $this->collectionIndex) {
            $this->collectionIndex = 0;
        }
    }

    private function currentCollection(): Iterator
    {
        return $this->collections[$this->collectionIndex];
    }
}