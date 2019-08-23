<?php


use PHPUnit\Framework\TestCase;

class PhpCoreTest extends TestCase
{
    public function testStrrpos()
    {
        $name = "\zzz\azeaze";
        $lastPos = strrpos($name, '\\');
        $this->assertEquals('\azeaze', mb_substr($name, $lastPos));
    }
}