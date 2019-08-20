<?php

namespace unit\PAG\Testing; 


use PAG\Testing\BlackboxedScriptRunner;
use PHPUnit\Framework\TestCase;

class ShutdownEventHandlerTest extends TestCase
{
    private const MANUAL = __DIR__ . "/../../../ManualTesting";

    public function testRegisterErrorShutdownFunction()
    {
        $output =
            BlackboxedScriptRunner::fetchScriptStdout(realpath(self::MANUAL . "/testRegisterErrorShutdownHandler.php"));

        $this->assertEquals("END OF TESTS (Error)\n", $output);
    }

    public function testRegisterShutdownHandler()
    {
        $output =
            BlackboxedScriptRunner::fetchScriptStdout(self::MANUAL . "/testRegisterShutdownHandler.php");

        $this->assertEquals("END OF TESTS\n", $output);
    }

    public function testBoth()
    {
        $output =
            BlackboxedScriptRunner::fetchScriptStdout(self::MANUAL . "/testBoth.php");
        $this->assertEquals("END OF TESTS (Error)\nEND OF TESTS\n", $output);
    }

    public function testCancelError()
    {
        $output = BlackboxedScriptRunner::fetchScriptStdout(self::MANUAL . "/testCancelError.php");
        $this->assertEquals("in cancel error\n", $output);
    }

    public function testCancel()
    {
        $output = BlackboxedScriptRunner::fetchScriptStdout(self::MANUAL . "/testCancel.php");
        $this->assertEquals("in cancel\n", $output);
    }

    public function testContext()
    {
        $output = BlackboxedScriptRunner::fetchScriptStdout(self::MANUAL . "/testContext.php");
        $this->assertEquals("ok context\nok error\n", $output);
    }
}
