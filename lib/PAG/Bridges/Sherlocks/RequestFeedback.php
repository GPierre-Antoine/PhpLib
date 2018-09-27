<?php

namespace PAG\Bridges\Sherlocks;


class RequestFeedback
{

    private $message;
    private $error;
    private $code;

    public function __construct(string $response)
    {
        $parsed = explode('!', $response);

        $this->code    = $parsed[1];
        $this->error   = $parsed[2];
        $this->message = $parsed[3];
    }

    public function isError(): bool
    {
        return $this->code != 0;
    }

    public function isInvalid(): bool
    {
        return $this->code == "" && $this->error == "";
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getError(): string
    {
        return $this->error;
    }
}