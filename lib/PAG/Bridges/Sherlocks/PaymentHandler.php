<?php

namespace PAG\Bridges\Sherlocks;


use RuntimeException;

class PaymentHandler
{
    const ERROR_TITLE            = "Sherlocks PaymentHandler Error";
    const DEFAULT_EXECUTABLE_DIR = "/usr/local/bin/sherlocks";

    private $merchantId;
    private $requestBinary;
    private $pathFile;
    private $responseBinary;
    private $prod;
    private $executedString;

    protected function __construct(string $merchantId, string $requestBinary, string $responseBinary, string $pathFile,
        bool $prod)
    {

        self::assertExecutable("request", $requestBinary);
        self::assertExecutable("response", $responseBinary);
        self::assertReadable("pathfile", $pathFile);

        $this->merchantId     = $merchantId;
        $this->requestBinary  = $requestBinary;
        $this->pathFile       = $pathFile;
        $this->responseBinary = $responseBinary;
        $this->prod           = $prod;
    }

    private static function assertExecutable(string $name, string $file): void
    {
        self::assertReadable($name, $file);
        if (!is_executable($file)) {
            self::throwError("Non-executable $name «{$file}»");
        }
    }

    private static function assertReadable(string $name, string $file): void
    {
        self::assertExists($name, $file);
        if (!is_readable($file)) {
            self::throwError("Unreadable $name «{$file}»");
        }
    }

    private static function throwError(string $string): void
    {
        throw new RuntimeException(self::ERROR_TITLE . " : $string");
    }

    public static function makeProdHandler(string $prodId, string $pathfile, string $directory = null)
    {
        $directory = $directory ?: self::DEFAULT_EXECUTABLE_DIR;
        return new PaymentHandler($prodId, "$directory/request", "$directory/response", $pathfile, true);
    }

    public static function makeTestHandler(string $testId, string $pathfile, string $directory = null)
    {
        $directory = $directory ?: self::DEFAULT_EXECUTABLE_DIR;
        return new PaymentHandler($testId, "$directory/request", "$directory/response", $pathfile, false);
    }

    private static function assertExists(string $name, string $file): void
    {
        if (!file_exists($file)) {
            self::throwError("$name not found at path «{$file}»");
        }
    }

    public function callRequest(RequestInfo $options): RequestFeedback
    {
        $options->merchant_id = $this->merchantId;
        $options->pathfile    = $this->pathFile;
        $result               = exec($this->executedString = $this->requestBinary . $options);
        return new RequestFeedback($result);
    }

    public function callResponse(ResponseInfo $info): ResponseFeedback
    {
        $info->pathfile = $this->pathFile;
        $result         = exec($this->responseBinary . $info);
        return new ResponseFeedback($result);
    }

    public function getPathFile(): string
    {
        return $this->pathFile;
    }

    public function getRequestBinary(): string
    {
        return $this->requestBinary;
    }

    public function getResponseBinary(): string
    {
        return $this->responseBinary;
    }

    public function isProd(): bool
    {
        return $this->prod;
    }

    public function getExecutedString(): string
    {
        return $this->executedString;
    }
}