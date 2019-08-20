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
}