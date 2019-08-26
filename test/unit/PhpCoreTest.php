<?php


use PHPUnit\Framework\TestCase;

class PhpCoreTest extends TestCase
{
    public function testStrrpos()
    {
        $name = "\zzz\azeaze";
        $lastPos = mb_strrpos($name, '\\');
        $this->assertEquals('\azeaze', mb_substr($name, $lastPos));
    }
}