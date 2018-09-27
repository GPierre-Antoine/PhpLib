<?php

namespace PAG\Bridges\Sherlocks;


class ResponseInfo
{
    use AssertDefined;
    use NoExceptionTostring;
    public $message;
    public $pathfile;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public static function makeFromPostData(): ResponseInfo
    {
        return new self($_POST['DATA']);
    }

    public function computeString(): string
    {
        $array = [];
        $this->assertDefined('message', 'pathfile');
        foreach ($this as $key => $value) {
            $array[] = "$key=$value";
        }
        return ' ' . escapeshellcmd(join(' ', $array));
    }
}