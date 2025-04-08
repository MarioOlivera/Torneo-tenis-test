<?php
namespace Src\Infrastructure\Http\Controllers;

use Src\Infrastructure\Http\Request;
use Src\Infrastructure\Http\Responses\Envelope;
use Src\Infrastructure\Http\Responses\ErrorResponse;

use Src\Application\UseCases\Player\ListPlayersUseCase;
use Src\Application\UseCases\Player\CreatePlayerUseCase;
use Src\Application\UseCases\Player\UpdatePlayerUseCase;
use Src\Application\UseCases\Player\ShowPlayerUseCase;
use Src\Application\DTOs\Player\CreatePlayerDTO;
use Src\Application\DTOs\Player\UpdatePlayerDTO;

use Src\Domain\Exceptions\DomainException;

use OpenApi\Annotations as OA;


class PlayerController {
    public function __construct(
        private ListPlayersUseCase $listPlayersUseCase,
        private CreatePlayerUseCase $createPlayerUseCase,
        private UpdatePlayerUseCase $updatePlayerUseCase,
        private ShowPlayerUseCase $showPlayerUseCase
    ) {}

    /**
    * @OA\Get(
    *     path="/api/v1/players",
    *     tags={"Players"},
    *     summary="List all players",
    *     description="Returns a list of all players",
    * @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *         @OA\JsonContent(
    *             allOf={
    *                 @OA\Schema(ref="#/components/schemas/Envelope"),
    *                 @OA\Schema(
    *                     @OA\Property(
    *                         property="data",
    *                         type="array",
    *                         @OA\Items(ref="#/components/schemas/Player")
    *                     ),
    *                     @OA\Property(property="response", example=true),
    *                     @OA\Property(property="errors", example=null)
    *                 )
    *             }
    *         )
    *     )
    * )
    */
    public function index(Request $request) : Envelope {
        $response = new Envelope();

        try
        {
            $response->setData($this->listPlayersUseCase->execute()->toArray());
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }

        return $response;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/players",
     *     tags={"Players"},
     *     summary="Create a new player",
     *     description="Register a new player",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Player data",
     *         @OA\JsonContent(ref="#/components/schemas/PlayerCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Player created successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         ref="#/components/schemas/Player"
     *                     ),
     *                     @OA\Property(property="response", example=true),
     *                     @OA\Property(property="errors", example=null)
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
    *         response=400,
    *         description="Validation error",
    *         @OA\JsonContent(
    *             allOf={
    *                 @OA\Schema(ref="#/components/schemas/Envelope"),
    *                 @OA\Schema(
    *                     @OA\Property(property="response", example=false),
    *                     @OA\Property(property="data", example=null),
    *                     @OA\Property(
    *                         property="errors",
    *                         example={
    *                             "code": "2",
    *                             "description": "Validation error",
    *                             "other_info": "{'name':'Name is required','skill_level':'Skill level is required','gender_id':'gender_id is required'}"
    *                         }
    *                     )
    *                 )
    *             }
    *         )
    *     ),
    * )
    */
    public function store(Request $request): Envelope {
        $response = new Envelope();

        try
        {
            $dto = CreatePlayerDTO::fromRequest($request->getBody());
            $response->setData($this->createPlayerUseCase->execute($dto)->toArray());
            $response->setHttpCode(201);
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }

        return $response;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/players/{id}",
     *     tags={"Players"},
     *     summary="Get a specific player",
     *     description="Returns detailed information about a single player",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the player to retrieve",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         ref="#/components/schemas/Player"
     *                     ),
     *                     @OA\Property(property="response", example=true),
     *                     @OA\Property(property="errors", example=null)
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Player not found",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(property="response", example=false),
     *                     @OA\Property(
     *                         property="errors",
     *                         example={
     *                             "code": "8",
     *                             "description": "Player not found",
     *                             "other_info": "Player 1000 not found"
     *                         }
     *                     )
     *                 )
     *             }
     *         )
     *     )
     * )
     */
    public function show(Request $request, int $id): Envelope {
        $response = new Envelope();

        try
        {
            $response->setData($this->showPlayerUseCase->execute($id)->toArray());
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }

        return $response;
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/players/{id}",
     *     tags={"Players"},
     *     summary="Update partial player information",
     *     description="Updates one or more fields of an existing player. All fields are optional.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the player to update",
     *         @OA\Schema(type="integer", format="int64", example=3)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Player data",
     *         @OA\JsonContent(ref="#/components/schemas/PlayerUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Player updated successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         ref="#/components/schemas/Player"
     *                     ),
     *                     @OA\Property(property="response", example=true),
     *                     @OA\Property(property="errors", example=null)
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Player not found",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(property="response", example=false),
     *                     @OA\Property(
     *                         property="errors",
     *                         example={
     *                             "code": "404",
     *                             "description": "Player not found",
     *                             "other_info": null
     *                         }
     *                     )
     *                 )
     *             }
     *         )
     *     )
     * )
     */
    public function update(Request $request, int $id): Envelope {
        $response = new Envelope();
        try {
            $id = (int) $id;
            $dto = UpdatePlayerDTO::fromRequest($id, $request->getBody());
            $updatedPlayer = $this->updatePlayerUseCase->execute($dto);
            $response->setData($updatedPlayer->toArray());
            $response->setHttpCode(200);
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }
        
        return $response;
    }
}