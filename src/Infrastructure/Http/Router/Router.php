<?php
namespace Src\Infrastructure\Http\Router;

use \Src\Infrastructure\Http\Container;
use Src\Infrastructure\Http\Responses\Envelope;
use Src\Infrastructure\Http\Request;

final class Router {
    private static array $routes = [];

    public static function get(string $uri, array $action): void {
        self::addRoute('GET', $uri, $action);
    }

    public static function post(string $uri, array $action): void {
        self::addRoute('POST', $uri, $action);
    }

    public static function patch(string $uri, array $action): void {
        self::addRoute('PATCH', $uri, $action);
    }

    public static function put(string $uri, array $action): void {
        self::addRoute('PUT', $uri, $action);
    }

    private static function addRoute(string $method, string $uri, array $action): void {
        self::$routes[$method][$uri] = $action;
    }

    public static function dispatch(string $requestMethod, string $requestUri, Container $container): void {
        foreach (self::$routes[$requestMethod] ?? [] as $routeUri => $action) {
            if (self::matches($routeUri, $requestUri)) {
                [$controllerClass, $method] = $action;

                $controller = $container->resolve($controllerClass);

                $request = new Request($_GET ?? [], self::getJsonInput() ?? []);

                $params = array_merge(
                    ["request" => $request],
                    self::extractParams($routeUri, $requestUri),
                );

                $envelope = $controller->$method(...$params);

                header('Content-Type: application/json');
                http_response_code($envelope->getHttpCode());
                echo json_encode($envelope);
                return;
            }
        }

        $envelope = Envelope::get404Response();
        header('Content-Type: application/json');
        http_response_code($envelope->getHttpCode());
        echo json_encode($envelope);
        return;
    }

    private static function matches(string $routeUri, string $requestUri): bool {
        return preg_match(self::convertToRegex($routeUri), $requestUri);
    }

    private static function convertToRegex(string $uri): string {
        return '#^' . preg_replace('/\{(\w+)\}/', '(?<$1>\d+)', $uri) . '$#';
    }

    private static function extractParams(string $routeUri, string $requestUri): array {
        preg_match(self::convertToRegex($routeUri), $requestUri, $matches);
        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }

    private static function getJsonInput(): array {
        $json = file_get_contents('php://input');
        return json_decode($json, true) ?? [];
    }
}