<?php
namespace Src\Infrastructure\Http;

class Container {
    private array $bindings = [];

    public function bind(string $key, callable $resolver): void {
        $this->bindings[$key] = $resolver;
    }

    public function resolve(string $key): mixed {
        if (!isset($this->bindings[$key])) {
            throw new \Exception("No binding found for {$key}");
        }
        return call_user_func($this->bindings[$key]);
    }
}