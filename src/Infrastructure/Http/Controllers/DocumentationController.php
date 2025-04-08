<?php
namespace Src\Infrastructure\Http\Controllers;

use Src\Infrastructure\Http\Request;

use Src\Infrastructure\Http\Responses\ViewResponse;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API Tennis Tournaments",
 *     version="1.0.0",
 *     description="API documentation for Tournament Tennis"
 * )
 *
 * @OA\Server(
 *     url=BASE_URL,
 *     description="URL API"
 * )
 */

class DocumentationController {
    public function __construct() {}

    public function index(Request $request) : ViewResponse {
        $data = [
            "url_swagger" => $_ENV['BASE_URL']."/openapi.yaml"
        ];

        return new ViewResponse(VIEW_PATH . 'documentation.php', $data);
    }
}