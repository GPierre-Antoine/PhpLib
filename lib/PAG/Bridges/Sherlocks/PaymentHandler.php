<?php

namespace PAG\Bridges\Sherlocks;


class PaymentHandler
{
    private $merchantId;
    private $requestBinary;
    private $pathFile;
    private $responseBinary;
    private $testHandler;
    private $executedString;

    public function __construct(string $merchantId, string $requestBinary, string $responseBinary, string $pathFile, bool $testHandler)
    {
        $this->merchantId     = $merchantId;
        $this->requestBinary  = $requestBinary;
        $this->pathFile       = $pathFile;
        $this->responseBinary = $responseBinary;
        foreach ([
                     'request'  => $requestBinary,
                     'response' => $responseBinary,
                     'pathfile' => $pathFile,
                 ] as $key => $file) {
            $this->controlFileOk($file, $key);
        }

        $this->testHandler = $testHandler;
    }

    private function controlFileOk(string $file, string $key):void
    {
        if (!file_exists($file)) {
            throw new \RuntimeException("Sherlock PaymentHandler error : Invalid $file as $key");
        }
        if (!is_readable($file)) {
            throw new \RuntimeException("Sherlock PaymentHandler error : Unreadable $file as $key");
        }
        if ($key != 'pathfile' && !is_executable($file)) {
            throw new \RuntimeException("Sherlock PaymentHandler error : Unexecutable $file as $key");
        }
    }

    public function callRequest(RequestInfo $options):RequestFeedback
    {
        $options->merchant_id = $this->merchantId;
        $options->pathfile    = $this->pathFile;
        $result               = exec($this->executedString = $this->requestBinary . $options);
        return new RequestFeedback($result);
    }

    public function callResponse(ResponseInfo $info):ResponseFeedback
    {
        $info->pathfile = $this->pathFile;
        $result         = exec($this->responseBinary . $info);
        return new ResponseFeedback($result);
    }

    public function getPathFile():string
    {
        return $this->pathFile;
    }

    public function getRequestBinary():string
    {
        return $this->requestBinary;
    }

    public function getResponseBinary():string
    {
        return $this->responseBinary;
    }

    public function isTestSession():bool
    {
        return $this->testHandler;
    }

    public function getExecutedString():string
    {
        return $this->executedString;
    }
}