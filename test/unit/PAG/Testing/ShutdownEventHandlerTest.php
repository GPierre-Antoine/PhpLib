<?php

use PAG\Testing\BlackboxedScriptRunner;
use PHPUnit\Framework\TestCase;

class ShutdownEventHandlerTest extends TestCase
{
    public function testRegisterErrorShutdownFunction()
    {
        $output =
            BlackboxedScriptRunner::fetchScriptStdout(__DIR__ . "/../ManualTesting/testRegisterErrorShutdownHandler.php");

        $this->assertEquals("Bootstrap OK\nEND OF TESTS (Error)\n", $output);
    }

    public function testRegisterShutdownHandler()
    {
        $output =
            BlackboxedScriptRunner::fetchScriptStdout(__DIR__ . "/../ManualTesting/testRegisterShutdownHandler.php");

        $this->assertEquals("Bootstrap OK\nEND OF TESTS\n", $output);
    }

    public function testBoth()
    {
        $output =
            BlackboxedScriptRunner::fetchScriptStdout(__DIR__ . "/../ManualTesting/testBoth.php");
        $this->assertEquals("Bootstrap OK\nEND OF TESTS (Error)\nEND OF TESTS\n", $output);
    }

    public function testCancelError()
    {
        $output = BlackboxedScriptRunner::fetchScriptStdout(__DIR__ . "/../ManualTesting/testCancelError.php");
        $this->assertEquals("Bootstrap OK\n", $output);
    }

    public function testCancel()
    {
        $output = BlackboxedScriptRunner::fetchScriptStdout(__DIR__ . "/../ManualTesting/testCancel.php");
        $this->assertEquals("Bootstrap OK\n", $output);
    }

    public function testContext()
    {
        $output = BlackboxedScriptRunner::fetchScriptStdout(__DIR__ . "/../ManualTesting/testContext.php");
        $this->assertEquals("Bootstrap OK\nok context\nok error\n", $output);
    }
}
