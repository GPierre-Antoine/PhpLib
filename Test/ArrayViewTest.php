<?php

use PAG\Collection\ArrayView;
use PHPUnit\Framework\TestCase;

class ArrayViewTest extends TestCase
{

    public function testOffsetGet()
    {
        $refArray    = ['key' => 'value'];
        $view = new ArrayView($refArray, "strtoupper");
        $this->assertEquals("VALUE", "$view[key]");
    }

    public function testOffsetGetMulti()
    {
        $refArray     = ['key' => '<value>'];
        $view1  = new ArrayView($refArray, "strtoupper");
        $view2 = new ArrayView($view1, function ($item) {
            return htmlentities($item, ENT_QUOTES);
        });
        $this->assertEquals("&lt;VALUE&gt;", "$view2[key]");
    }

    public function testOffsetUnset()
    {
        $array        = ['key' => 'y'];
        $nullFunction = function () {
        };
        (new ArrayView($array, $nullFunction))->offsetUnset('key');
        $this->assertEmpty($array);
    }

    public function testOffsetSet()
    {
        $array       = [];
        $view        = new ArrayView($array, 'strtoupper');
        $view['key'] = 'value';
        $this->assertArrayHasKey('key', $array);
        $this->assertEquals('VALUE', $array['key']);
    }

    public function testOffsetSetNoOffset()
    {
        $array  = [];
        $view   = new ArrayView($array, 'strtoupper');
        $view[] = 'value';
        $this->assertArrayHasKey(0, $array);
        $this->assertEquals('VALUE', $array[0]);
    }
}
