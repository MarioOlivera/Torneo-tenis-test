<?php
namespace Src\Infrastructure\Http\Responses;

final class Envelope implements \JsonSerializable
{
    private bool $response;
    private array | null $data;
    private ErrorResponse | null $errors;
    private int $httpCode;

    public function __construct()
    {
        $this->response = true;
        $this->data = [];
        $this->errors = null;
        $this->httpCode = 200;
    }

    public function setResponse(bool $response) : void {
        $this->response = $response;
    }

    public function setData(array | null $data) : void
    {
        $this->data = $data;
    }

    public function setErrors(ErrorResponse | null $errors) : void
    {
        $this->errors = $errors;
    }

    public function setHttpCode(int $httpCode) : void
    {
        $this->httpCode = $httpCode;
    }

    public function getResponse() : bool
    {
        return $this->response;
    }

    public function getData() : array | null
    {
        return $this->data;
    }

    public function getErrors() : ErrorResponse | null
    {
        return $this->errors;
    }

    public function getHttpCode() : int
    {
        return $this->httpCode;
    }

    public function jsonSerialize(): array {
        return [
            "response" => $this->response,
            "data" => $this->data,
            "errors" => $this->errors,
        ];
    }
}