<?php
namespace Src\Infrastructure\Http\Controllers;

use Src\Infrastructure\Http\Request;
use Src\Infrastructure\Http\Responses\Envelope;
use Src\Infrastructure\Http\Responses\ErrorResponse;

use Src\Application\UseCases\Tournament\CreateTournamentUseCase;
use Src\Application\UseCases\Tournament\UpdateTournamentUseCase;
use Src\Application\UseCases\Tournament\ListTournamentsUseCase;
use Src\Application\UseCases\Tournament\CancelTournamentUseCase;
use Src\Application\UseCases\Tournament\PlayTournamentUseCase;
use Src\Application\UseCases\Tournament\RegisterPlayerToTournamentUseCase;

use Src\Application\DTOs\Tournament\CreateTournamentDTO;
use Src\Application\DTOs\Tournament\UpdateTournamentDTO;
use Src\Application\DTOs\Tournament\RegisterPlayerTournamentDTO;
use Src\Application\DTOs\Tournament\ListTournamentsDTO;

use Src\Domain\Exceptions\DomainException;

class TournamentController {
    public function __construct(
        private ListTournamentsUseCase $listTournamentsUseCase,
        private CreateTournamentUseCase $createTournamentUseCase,
        private UpdateTournamentUseCase $updateTournamentUseCase,
        private CancelTournamentUseCase $cancelTournamentUseCase,
        private PlayTournamentUseCase $playTournamentUseCase,
        private RegisterPlayerToTournamentUseCase $registerPlayerToTournamentUseCase
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/tournaments",
     *     tags={"Tournaments"},
     *     summary="List all tournaments",
     *     description="Returns a filtered list of tournaments based on optional query parameters",
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Filter tournaments starting on or after this date (YYYY-MM-DD format)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2025-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Filter tournaments ending on or before this date (YYYY-MM-DD format)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2025-12-31")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filter by tournament category: 1 - MEN'S, 2 - WOMEN'S",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             enum={1, 2},
     *             example=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status_id",
     *         in="query",
     *         description="Filter by tournament status: 1 - PENDING, 2 - PLAYED, 3 - CANCELLED",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             enum={1, 2, 3},
     *             example=1
     *         )
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
     *                         type="array",
     *                         @OA\Items(ref="#/components/schemas/Tournament")
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
            $dto = ListTournamentsDTO::fromRequest($request->getQuery());

            $response->setData($this->listTournamentsUseCase->execute($dto)->toArray());
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }
        return $response;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tournaments",
     *     tags={"Tournaments"},
     *     summary="Create a new tournament",
     *     description="Register a new tournament in the system",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Tournament data",
     *         @OA\JsonContent(
     *             required={"name", "category_id"},
     *             @OA\Property(property="name", type="string", example="2025 Season Tournament", minLength=3),
     *             @OA\Property(
     *                 property="category_id", 
     *                 type="integer", 
     *                 example=1,
     *                 description="1 - MEN'S, 2 - WOMEN'S",
     *                 enum={1, 2}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tournament created successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         ref="#/components/schemas/Tournament"
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
     *                             "other_info": "{'name':'Name is required','category_id':'category_id is required'}"
     *                         }
     *                     )
     *                 )
     *             }
     *         )
     *     )
     * )
     */
    public function store(Request $request): Envelope {
        $response = new Envelope();

        try
        {
            $dto = CreateTournamentDTO::fromRequest($request->getBody());
            $response->setData($this->createTournamentUseCase->execute($dto)->toArray());
            $response->setHttpCode(201);
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }

        return $response;
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/tournaments/{id}",
     *     tags={"Tournaments"},
     *     summary="Update tournament name",
     *     description="Updates only the name of an existing tournament. Category and status cannot be modified after creation.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the tournament to update",
     *         @OA\Schema(type="integer", format="int64", example=3)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="New tournament name",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Updated Tournament Name",
     *                 minLength=3,
     *                 description="New name for the tournament (minimum 3 characters)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tournament name updated successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         ref="#/components/schemas/Tournament"
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
     *                     @OA\Property(
     *                         property="errors",
     *                         example={
     *                             "code": "2",
     *                             "description": "Validation error",
     *                             "other_info": "{'name':'Name is required'}"
     *                         }
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tournament not found",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(property="response", example=false),
     *                     @OA\Property(
     *                         property="errors",
     *                         example={
     *                             "code": "3",
     *                             "description": "Tournament not found",
     *                             "other_info": "Tournament 25 not found"
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
            $dto = UpdateTournamentDTO::fromRequest($id, $request->getBody());
            $updated = $this->updateTournamentUseCase->execute($dto);
            $response->setData($updated->toArray());
            $response->setHttpCode(200);
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }

        return $response;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tournaments/{id}/cancel",
     *     tags={"Tournaments"},
     *     summary="Cancel a tournament",
     *     description="Cancels an existing tournament by its ID. No request body required.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the tournament to cancel",
     *         @OA\Schema(type="integer", format="int64", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tournament cancelled successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         ref="#/components/schemas/Tournament"
     *                     ),
     *                     @OA\Property(property="response", example=true),
     *                     @OA\Property(property="errors", example=null)
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tournament not found",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(property="response", example=false),
     *                     @OA\Property(
     *                         property="errors",
     *                         example={
     *                             "code": "3",
     *                             "description": "Tournament not found",
     *                             "other_info": "Tournament 25 not found"
     *                         }
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Tournament cannot be cancelled",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(property="response", example=false),
     *                     @OA\Property(
     *                         property="errors",
     *                         example={
     *                             "code": "4",
     *                             "description": "Tournament is not pending",
     *                             "other_info": "Tournament 1 is not pending"
     *                         }
     *                     )
     *                 )
     *             }
     *         )
     *     )
     * )
     */
    public function cancel(Request $request, int $id) : Envelope {
        $response = new Envelope();

        try
        {
            $response->setData($this->cancelTournamentUseCase->execute($id)->toArray());
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }

        return $response;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tournaments/{id}/registrations",
     *     tags={"Tournaments"},
     *     summary="Register a player to a tournament",
     *     description="Adds a player to the specified tournament",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the tournament",
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Player registration data",
     *         @OA\JsonContent(
     *             required={"player_id"},
     *             @OA\Property(
     *                 property="player_id",
     *                 type="integer",
     *                 example=2,
     *                 description="ID of the player to register"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Player registered successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         ref="#/components/schemas/TournamentRegistration"
     *                     ),
     *                     @OA\Property(property="response", example=true),
     *                     @OA\Property(property="errors", example=null)
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(property="response", example=false),
     *                     @OA\Property(
     *                         property="errors",
     *                         example={
     *                             "code": "3",
     *                             "description": "Tournament not found",
     *                             "other_info": "Tournament 25 not found"
     *                         }
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Player already registered",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(property="response", example=false),
     *                     @OA\Property(
     *                         property="errors",
     *                         example={
     *                             "code": "19",
     *                             "description": "Player already registered in tournament",
     *                             "other_info": "Player 2 is already registered in tournament 1"
     *                         }
     *                     )
     *                 )
     *             }
     *         )
     *     )
     * )
     */
    public function registerPlayer(Request $request, int $id) : Envelope {
        $response = new Envelope();

        try
        {
            $dto = RegisterPlayerTournamentDTO::fromRequest($id, $request->getBody());
            $response->setData($this->registerPlayerToTournamentUseCase->execute($dto)->toArray());
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }

        return $response;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tournaments/{id}/play",
     *     tags={"Tournaments"},
     *     summary="Play a tournament",
     *     description="Simulates all matches of a tournament and determines the winner",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the tournament to play",
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tournament played successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         ref="#/components/schemas/TournamentPlayResult"
     *                     ),
     *                     @OA\Property(property="response", example=true),
     *                     @OA\Property(property="errors", example=null)
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tournament not found",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Envelope"),
     *                 @OA\Schema(
     *                     @OA\Property(property="response", example=false),
     *                     @OA\Property(
     *                         property="errors",
     *                         example={
     *                             "code": "3",
     *                             "description": "Tournament not found",
     *                             "other_info": "Tournament 25 not found"
     *                         }
     *                     )
     *                 )
     *             }
     *         )
     *     )
     * )
     */
    public function play(Request $request, int $id) : Envelope {
        $response = new Envelope();

        try
        {
            $response->setData($this->playTournamentUseCase->execute($id)->toArray());
        }
        catch (DomainException $e) {
            $response = Envelope::fromDomainException($e);
        }

        return $response;
    }
}