<?php
namespace MarcinJean\LaravelVPic\Exceptions;

use Exception;

class VPicException extends Exception
{
    protected int $errorCode;
    protected string $errorText;

    public function __construct(int $errorCode, string $errorText)
    {
        parent::__construct($errorText, $errorCode);
        $this->errorCode = $errorCode;
        $this->errorText = $errorText;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function getErrorText(): string
    {
        return $this->errorText;
    }
}
