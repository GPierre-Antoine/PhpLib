<?php

namespace unit\PAG\Collection; 


use PAG\Collection\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{

    public function testFlip()
    {
        $array = ['A' => 'a', 'B' => 'b', 'C' => 'c'];

        $collection = new Collection($array);
        $this->assertSame(array_flip($array), $collection->flip()->getArrayCopy());
    }

    public function test__toString()
    {
        $collection = new Collection(['A', 'B', 'C']);
        $this->assertSame("[A, B, C]", "$collection");
    }

    public function testPush()
    {
        $collection = new Collection();
        $collection->push('a');
        $this->assertSame(['a'], $collection->getArrayCopy());
    }

    public function testOffsetGet()
    {
        $val           = 1;
        $collection    = new Collection();
        $collection[0] = $val;
        $this->assertSame($val, $collection[0]);
    }

    public function testKeys()
    {
        $array      = ["a" => 1, "b" => 2];
        $collection = new Collection($array);
        $this->assertSame(array_keys($array), $collection->keys()->getArrayCopy());
    }

    public function testIntersectRecursive()
    {
        $a2         = [
            new Collection([1, 2, 4]),
            new Collection([4, 5, 6]),
        ];
        $collection = new Collection($a2);

        $this->assertSame([2 => 4], $collection->intersectRecursive()->getArrayCopy());
    }

    public function test__construct()
    {
        $array      = [1, 2, 3, 4, 5];
        $collection = new Collection($array);
        $this->assertSame($array, $collection->getArrayCopy());
    }

    public function testFilter()
    {
        $array      = [1, 2, 3, 4, 5];
        $function   = function ($value) {
            return $value > 3;
        };
        $collection = new Collection($array);
        $this->assertSame(array_filter($array, $function),
            $collection->filter($function)->getArrayCopy());
    }

    public function testCount()
    {
        $array      = [1, 2, 3, 4, 5];
        $collection = new Collection($array);
        $this->assertSame(count($array), count($collection));
    }

    public function testMap()
    {
        $array      = [1, 2, 3, 4, 5];
        $collection = new Collection($array);
        $map        = function ($n) {
            return $n * 2;
        };
        $this->assertSame(array_map($map, $array), $collection->map($map)->getArrayCopy());
    }

    public function testOffsetSet()
    {
        $collection   = new Collection();
        $collection[] = 2;
        $this->assertSame([2], $collection->getArrayCopy());
    }

    public function testSort()
    {
        $array      = [1, 2, 3];
        $collection = new Collection([3, 2, 1]);
        $collection->sort(function ($first, $second) {
            return $first - $second;
        });
        $this->assertEquals($array, $collection->getArrayCopy());
    }

    public function testDuplicate()
    {
        $array  = new Collection([1, 2, 3]);
        $dup    = $array->duplicate();
        $dup[0] = 5;
        $this->assertEquals([1, 2, 3], $array->getArrayCopy());
        $this->assertEquals([5, 2, 3], $dup->getArrayCopy());
    }

    public function testGetArrayCopy()
    {
        $array      = [1, 2, 3];
        $collection = new Collection($array);
        $this->assertSame($array, $collection->getArrayCopy());
    }

    public function testValues()
    {
        $array = new Collection(['a' => '1', 'b' => '2', 'c' => '3']);
        $this->assertEquals(['1', '2', '3'], $array->values()->getArrayCopy());
    }


    public function testIsAssociative()
    {
        $array = new Collection(['a' => '1', 'b' => '2', 'c' => '3']);
        $this->assertEquals(true, $array->isAssociative());
        $this->assertEquals(false, $array->values()->isAssociative());
    }

    public function testPop()
    {
        $actual = new Collection([2, 3, 1]);
        $value  = $actual->pop();
        $this->assertEquals(1, $value);
        $this->assertEquals([2, 3], $actual->getArrayCopy());
    }

    public function testShift()
    {
        $actual = new Collection([1, 2, 3]);
        $value  = $actual->shift();
        $this->assertEquals(1, $value);
        $this->assertEquals([2, 3], $actual->getArrayCopy());
    }

    public function testReduce()
    {
        $array      = [1, 2, 3];
        $collection = new Collection($array);
        $this->assertEquals(6,
            $collection->reduce(function ($f, $s) {
                return $f + $s;
            }));
    }

    public function testSlice()
    {
        $array      = [1, 2, 3, 4, 5];
        $collection = new Collection($array);
        $this->assertEquals([3, 4, 5], $collection->slice(2)->getArrayCopy());
    }

    public function testHasValue()
    {
        $col = new Collection([1, 2, 3]);
        $this->assertEquals(true, $col->hasValue(3));
    }

    public function testOffsetUnset()
    {
        $col = new Collection([1, 2, 3]);
        unset($col[0]);
        $this->assertEquals([1 => 2, 2 => 3], $col->getArrayCopy());
    }

    public function testFind()
    {
        $col = new Collection([1, 2, 3]);
        $this->assertEquals(2, $col->find(3));
    }

    public function testAbsorb()
    {
        $collection  = new Collection();
        $collection2 = new Collection([1, 2, 3]);
        $collection->absorb($collection2);
        $this->assertEquals($collection2, $collection);
    }

    public function testFromRange()
    {
        $a1 = new Collection([1, 2, 4]);

        $actual = $a1->fromRange(2, 3, 1);
        $this->assertEquals([4], $actual->getArrayCopy());
    }

    public function testIntersect()
    {
        $a1 = new Collection([1, 2, 4]);
        $a2 = new Collection([4, 5, 6]);


        $this->assertEquals([2 => 4], $a1->intersect($a2)->getArrayCopy());
    }

    public function testFirst()
    {
        $array = new Collection([5 => 'first', 1 => 'second']);
        $this->assertEquals('first', $array->first());
    }

    public function testReverse()
    {
        $array1 = (new Collection([1, 2, 3]))->reverse();

        $this->assertEquals([3, 2, 1], $array1->getArrayCopy());
    }

    public function testExplode()
    {
        $string = '1,2,3';
        $this->assertEquals(['1', '2', '3'], Collection::explode(',', $string)->getArrayCopy());
    }

    public function testOffsetExists()
    {
        $i     = 'INDEX';
        $x     = new Collection();
        $x[$i] = true;

        $this->assertEquals(true, isset($x[$i]));
    }

    public function testConcat()
    {
        $array1 = new Collection([1, 2, 3]);
        $array2 = new Collection([4, 5, 6]);
        $array3 = [1, 2, 3, 4, 5, 6];

        $this->assertEquals($array3, $array1->concat($array2)->getArrayCopy());


    }

    public function testJoin()
    {
        $string = '1,2,3';
        $this->assertEquals($string, Collection::explode(',', $string)->join(','));
    }

    public function testUnshift()
    {
        $actual = new Collection([1, 2, 3]);
        $actual->unshift(0);
        $this->assertEquals([0, 1, 2, 3], $actual->getArrayCopy());
    }

    public function testMakeCollection()
    {
        $expected = [1, 2, 3];
        $actual   = Collection::makeCollection([1, 2, 3], [])->getArrayCopy();
        $this->assertEquals($expected, $actual);
    }

    public function testWalk()
    {
        $collection = new Collection([
            'A' => 1,
            'B' => 2,
            'C' => 3,
        ]);
        $array      = [];
        $walk       = $collection->walk(function (&$value, $key) use (&$array) {
            $value++;
            $array[$key] = $value;
        });

        $this->assertEquals($array, $walk->getArrayCopy());
        $this->assertNotEquals($array, $collection->getArrayCopy());

    }

    public function testCombine()
    {
        $array1 = Collection::explode(',', 'a,b,c');
        $array2 = Collection::explode(',', '1,2,3');
        $this->assertEquals(['a' => '1', 'b' => '2', 'c' => '3'], $array1->combine($array2)->getArrayCopy());
    }
}
