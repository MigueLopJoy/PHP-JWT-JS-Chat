<?php

class ErrorHandler
{
    private int $errorCode;
    private string $field;
    private string $message;

    public function __construct(
        int $errorCode,
        string $field,
        string $message
    ) {
        $this->errorCode = $errorCode;
        $this->field = $field;
        $this->message = $message;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): void
    {
        $this->field = $field;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function throwError()
    {
        http_response_code($this->errorCode);
        return array(
            "error" => [
                "responseCode" => $this->errorCode,
                "field" => $this->field,
                "message" => $this->message
            ]
        );
    }
}
