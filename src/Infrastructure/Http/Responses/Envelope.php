<?php
namespace Src\Infrastructure\Http\Responses;

use Src\Domain\Exceptions\DomainException;
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

    public static function get404Response() : Envelope {
        $envelope = new Envelope();
        $envelope->setResponse(false);
        $envelope->setErrors(new ErrorResponse(\Src\Domain\Enums\ErrorCode::NOT_FOUND_RESOURCE, "Not Found"));
        $envelope->setHttpCode(404);
        return $envelope;
    }

    public static function fromDomainException(DomainException $e) : Envelope {
        $envelope = new Envelope();
        $envelope->setResponse(false);
        $envelope->setErrors(new ErrorResponse(
            $e->getErrorCode(),
            $e->getMessage()
        ));
        $envelope->setHttpCode($e->getHttpCode());
        return $envelope;
    }

    public function jsonSerialize(): array {
        return [
            "response" => $this->response,
            "data" => $this->data,
            "errors" => $this->errors,
        ];
    }
}