<?php


namespace unit\Core\Datastructure;


use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    public function testArrayKey()
    {
        $firstArray = ['key' => 'value'];
        $secondArray = array_map(static function ($item) {
            return $item;
        },
            $firstArray);
        $this->assertArrayHasKey('key', $secondArray);
        $thirdArray = array_filter($firstArray,
            static function () {
                return true;
            });
        $this->assertArrayHasKey('key', $thirdArray);
    }

    public function testConcat()
    {
        $first = [2 => 'a', 3 => 'z', 4 => 'e', 'w'];
        $second = [5 => 'r', 6 => 't', 4 => 'v', 'l'];
        $this->assertEquals([2 => 'a', 3 => 'z', 4 => 'e', 5 => 'w', 6 => 't', 7 => 'l'], $first + $second);
        $this->assertEquals(['a', 'z', 'e', 'w', 'r', 't', 'v', 'l'], array_merge($first, $second));
    }

    public function testEmptyShift()
    {
        $array = [];
        $this->assertNull(array_shift($array));
    }


    public function testForeach()
    {
        $collection = [1, 2, 3];
        $collection2 = [];
        foreach ($collection as $item) {
            $collection2[] = $item;
            $collection[2] = 4;
        }
        $this->assertEquals('123', join('', $collection2));
    }

    public function testForeachReference()
    {
        $collection = [1, 2, 3];
        $collection2 = [];
        foreach ($collection as &$item) {
            $collection2[] = $item;
            $collection[2] = 4;
        }
        $this->assertEquals('124', join('', $collection2));
    }

    public function testForeachReferencePop()
    {
        $collection = [1, 2];
        foreach ($collection as &$item) {
            array_pop($collection);
        }
        $this->assertEquals('1', join('', $collection));
    }

    public function testForeachPop()
    {
        $collection = [1, 2];
        foreach ($collection as $item) {
            array_pop($collection);
        }
        $this->assertEmpty($collection);
    }
}