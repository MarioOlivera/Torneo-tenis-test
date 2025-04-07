<?php
namespace Src\Infrastructure\Http;

class Request {
    private array $query;
    private array $body;

    public function __construct(array $query = [], array $body=[])
    {
        $this->query = $query;
        $this->body = $body;
    }
    public function all(): array {
        return array_merge($this->query, $this->body);
    }
    public function input(string $key, $default = null) {
        return $this->all()[$key] ?? $default;
    }

    public function getQuery(): array {
        return $this->query;
    }

    public function getBody(): array {
        return $this->body;
    }
}
