<?php

namespace unit\PAG\Connection\Utilitary; 


use PAG\Connection\Utilitary\NetrcParser;
use ParseError;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class NetrcParserTest extends TestCase
{

    public function test__construct()
    {
        $parser = $this->makeCorrectParser();
        $this->assertNotNull($parser);
    }

    public function makeCorrectParser()
    {
        return new NetrcParser("machine ftp.host.fr login login-value password password-value");
    }

    public function test_bad_construct()
    {
        $this->expectException(ParseError::class);
        new NetrcParser("machine ftp.host.fr login login-value password");
    }

    public function testGetCouple()
    {
        $parser = $this->makeCorrectParser();
        list($username, $password) = $parser->getCouple('ftp.host.fr');
        $this->assertEquals('login-value', $username);
        $this->assertEquals('password-value', $password);
    }

    public function testBadGetCouple()
    {
        $this->expectException(RuntimeException::class);
        $parser = $this->makeCorrectParser();
        $parser->getCouple('bad domain');
    }
}
