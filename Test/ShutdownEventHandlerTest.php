    <?php

    use PAG\Testing\BlackboxedScriptRunner;
    use PHPUnit\Framework\TestCase;

    class ShutdownEventHandlerTest extends TestCase
    {
        public function testRegisterShutdownHandler()
        {
            $output =
                BlackboxedScriptRunner::fetchScriptStdout(__DIR__ . "/../ManualTesting/testRegisterShutdownHandler.php");

            $this->assertEquals(
                file_get_contents(__DIR__ . "/../ManualTesting/testRegisterShutdownHandler.txt"),
                $output);
        }
    }
