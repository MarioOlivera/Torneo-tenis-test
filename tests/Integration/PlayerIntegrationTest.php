<?php
namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Src\Infrastructure\Http\Controllers\PlayerController;
use Src\Infrastructure\Http\Container;
use Src\Infrastructure\Http\Request;
use Src\Infrastructure\Http\Responses\Envelope;

/**
 * Test de integración para el endpoint de listado de jugadores.
 */
class PlayerIntegrationTest extends TestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        $this->container = require __DIR__ . '/../../src/Infrastructure/Config/container.php';
    }

    public function testIndexPlayersReturnsValidEnvelope()
    {
        // AGREGANDO UN PLAYER
        $body = [
            'name'          => 'Test Player',
            'skill_level'   => 85,
            'gender_id'     => 1, 
            'strength'      => 50,
            'speed'         => 60,
            'reaction_time' => null
        ];

        $queryParams = [];      
        $routeParams = [];  

        $request = new Request($queryParams, $body, $routeParams);

        $controller = $this->container->resolve(PlayerController::class);

        try {
            $envelope = $controller->store($request);
        } catch (\Exception $e) {
            $this->fail("Exception thrown during store: " . $e->getMessage());
        }

        $this->assertInstanceOf(Envelope::class, $envelope);
        $this->assertEquals(201, $envelope->getHttpCode());

        $responseData = $envelope->jsonSerialize()["data"];

        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('skill_level', $responseData);
        $this->assertArrayHasKey('gender_id', $responseData);
        $this->assertArrayHasKey('created_at', $responseData);
        $this->assertArrayHasKey('updated_at', $responseData);

        // IMPLEMENTANDO EL LISTADO
        $queryParams = [];
        $body = [];
        
        $request = new Request($queryParams, $body, []);

        $controller = $this->container->resolve(PlayerController::class);

        $envelope = $controller->index($request);

        $this->assertInstanceOf(Envelope::class, $envelope);
        $this->assertEquals(200, $envelope->getHttpCode());

        $responseData = json_decode(json_encode($envelope),true);
        $responseData = $responseData["data"];

        $this->assertIsArray($responseData);
        $this->assertNotEmpty($responseData);

        $firstPlayer = $responseData[0];
        
        $this->assertIsArray($firstPlayer, "El primer elemento no es un array");
        $this->assertArrayHasKey('id', $firstPlayer);
        $this->assertArrayHasKey('name', $firstPlayer);
        $this->assertArrayHasKey('skill_level', $firstPlayer);
        $this->assertArrayHasKey('gender_id', $firstPlayer);
        $this->assertArrayHasKey('created_at', $firstPlayer);
        $this->assertArrayHasKey('updated_at', $firstPlayer);
    }
}
