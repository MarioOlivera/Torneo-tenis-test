<?php
namespace Src\Infrastructure\Http\Responses;

use Src\Domain\Enums\ErrorCode;

class ErrorResponse implements \JsonSerializable {
    private string $code;
    private string $description;
    private string $other_info;

    public function __construct(ErrorCode $errorCode, string $other_info) {
        $this->code = (string)$errorCode->value;
        $this->description = $errorCode->description();
        $this->other_info = $other_info;
    }

    public function jsonSerialize(): array {
        return [
            'code' => $this->code,
            'description' => $this->description,
            'other_info' => $this->other_info,
        ];
    }
}
