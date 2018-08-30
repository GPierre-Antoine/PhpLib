<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 30/08/18
 * Time: 12:00
 */

namespace PAG\Connection\Utilitary;




use RuntimeException;

class NetrcParser
{

    private $tokens;
    private $logins;
    private $passwords;
    private $currentIndex = 0;

    public function __construct($string)
    {
        $this->tokens = preg_split('#\s+#', $string);
        $this->parse();
    }

    public function getCouple($machine):array
    {
        $this->assertKnowsMachine($machine);
        return [$this->logins[$machine], $this->passwords[$machine]];
    }

    private function parse():void
    {
        $currentMachine = '';
        for (; $this->currentIndex < count($this->tokens); ++$this->currentIndex) {
            switch ($this->tokens[$this->currentIndex]) {
                case 'machine':
                    ++$this->currentIndex;
                    $this->assertLength();
                    $currentMachine = $this->tokens[$this->currentIndex];
                    break;
                case 'login':
                    ++$this->currentIndex;
                    $this->assertLength();
                    $this->logins[$currentMachine] = $this->tokens[$this->currentIndex];
                    break;
                case 'password':
                    ++$this->currentIndex;
                    $this->assertLength();
                    $this->passwords[$currentMachine] = $this->tokens[$this->currentIndex];
                    break;
                default:
                    break;
            }
        }
    }

    private function assertKnowsMachine($machine):void
    {
        if (!array_key_exists($machine, $this->logins)) {
            throw new RuntimeException("No Logins for host $machine");
        }
        if (!array_key_exists($machine, $this->passwords)) {
            throw new RuntimeException("No Passwords for host $machine");
        }
    }

    private function assertLength():void
    {
        if ($this->currentIndex >= count($this->tokens)) {
            throw new \ParseError();
        }
    }
}