<?php
namespace Src\Infrastructure\Http\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Envelope",
 *     type="object",
 *     required={"response", "data", "errors"},
 *     @OA\Property(
 *         property="response",
 *         type="boolean",
 *         example=true,
 *         description="Indica si la solicitud fue exitosa"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             description="Contenido principal de la respuesta (varía por endpoint)"
 *         )
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         nullable=true,
 *         description="Detalles del error (si response=false)",
 *         @OA\Property(property="code", type="string", example="2"),
 *         @OA\Property(property="description", type="string", example="Validation error"),
 *         @OA\Property(property="other_info", type="string", example="")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="PlayerCreateRequest",
 *     type="object",
 *     required={"name", "skill_level", "gender_id"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Mario Olivera",
 *         description="Full name of the player"
 *     ),
 *     @OA\Property(
 *         property="skill_level",
 *         type="integer",
 *         example=95,
 *         description="Player's skill level (1-100)"
 *     ),
 *     @OA\Property(
 *         property="strength",
 *         type="integer",
 *         example=85,
 *         nullable=true,
 *         description="Required for Male players (1-100)"
 *     ),
 *     @OA\Property(
 *         property="speed",
 *         type="integer",
 *         example=90,
 *         nullable=true,
 *         description="Required for Male players (1-100)"
 *     ),
 *     @OA\Property(
 *         property="reaction_time",
 *         type="integer",
 *         example=90,
 *         nullable=true,
 *         description="Required for Female players (1-100)"
 *     ),
 *     @OA\Property(
 *         property="gender_id",
 *         type="integer",
 *         example=1,
 *         description="1 - Male | 2 - Female"
 *     ),
 *     description="Note: strength and speed are required when gender_id=1 (Male). reaction_time is required when gender_id=2 (Female)"
 * )
 * * @OA\Schema(
 *     schema="PlayerUpdateRequest",
 *     type="object",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Jose Olivera",
 *         description="Full name of the player",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="skill_level",
 *         type="integer",
 *         example=60,
 *         description="Player's skill level (1-100)",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="strength",
 *         type="integer",
 *         example=70,
 *         nullable=true,
 *         description="Strength level (1-100). Required if gender_id=1"
 *     ),
 *     @OA\Property(
 *         property="speed",
 *         type="integer",
 *         example=50,
 *         nullable=true,
 *         description="Speed level (1-100). Required if gender_id=1"
 *     ),
 *     @OA\Property(
 *         property="reaction_time",
 *         type="integer",
 *         example=10,
 *         nullable=true,
 *         description="Reaction time in ms. Required if gender_id=2"
 *     ),
 *     @OA\Property(
 *         property="gender_id",
 *         type="integer",
 *         example=1,
 *         nullable=true,
 *         description="1 - Male | 2 - Female"
 *     ),
 *     description="All fields are optional. Gender-specific validations apply when updating gender_id. If the player is registered for a pending tournament and their gender is changed, the endpoint will return an error."
 * )
 * 
 * @OA\Schema(
 *     schema="Player",
 *     type="object",
 *     required={"id", "name", "skill_level", "gender_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1,
 *         description="Unique identifier for the player"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Mario Olivera",
 *         description="Full name of the player"
 *     ),
 *     @OA\Property(
 *         property="skill_level",
 *         type="integer",
 *         example=90,
 *         description="Player's overall skill rating (1-100 scale)"
 *     ),
 *     @OA\Property(
 *         property="strength",
 *         type="integer",
 *         example=20,
 *         description="Physical strength rating (1-100 scale). Required for male players."
 *     ),
 *     @OA\Property(
 *         property="speed",
 *         type="integer",
 *         example=70,
 *         description="Movement speed rating (1-100 scale). Required for male players."
 *     ),
 *     @OA\Property(
 *         property="reaction_time",
 *         type="integer",
 *         nullable=true,
 *         example=null,
 *         description="Reaction time (1-100 scale). Required for female players."
 *     ),
 *     @OA\Property(
 *         property="gender_id",
 *         type="integer",
 *         example=1,
 *         description="Player's gender: 1 - Male | 2 - Female"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-04-07 21:36:47",
 *         description="Timestamp when the player record was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-04-07 21:36:47",
 *         description="Timestamp when the player record was last updated"
 *     ),
 *     description="Represents a tennis player with all attributes and performance metrics"
 * )
 * 
 * @OA\Schema(
 *     schema="Tournament",
 *     type="object",
 *     required={"id", "name", "category_id", "status_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=3,
 *         description="Unique tournament ID"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="2025 Season Tournament",
 *         description="Tournament name"
 *     ),
 *     @OA\Property(
 *         property="category_id",
 *         type="integer",
 *         example=2,
 *         description="Tournament category ID: 1 - MEN´S | 2 - WOMEN'S"
 *     ),
 *     @OA\Property(
 *         property="status_id",
 *         type="integer",
 *         example=1,
 *         description="Current tournament status ID"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-04-08 01:49:52",
 *         description="Creation date in Y-m-d H:i:s format"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-04-08 01:49:52",
 *         description="Last update date in Y-m-d H:i:s format"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="TournamentRegistration",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=5,
 *         description="Record ID"
 *     ),
 *     @OA\Property(
 *         property="player",
 *         ref="#/components/schemas/Player"
 *     ),
 *     @OA\Property(
 *         property="tournament",
 *         ref="#/components/schemas/Tournament"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-04-08 02:13:04"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-04-08 02:13:04"
 *     )
 * )
 * 
 *
 * @OA\Schema(
 *     schema="TournamentMatch",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="player_one",
 *         ref="#/components/schemas/Player"
 *     ),
 *     @OA\Property(
 *         property="player_two",
 *         ref="#/components/schemas/Player"
 *     ),
 *     @OA\Property(
 *         property="player_winner",
 *         ref="#/components/schemas/Player"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-04-08 02:18:33"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-04-08 02:18:33"
 *     )
 * )
 * 
 * 
 * @OA\Schema(
 *     schema="TournamentPlayResult",
 *     type="object",
 *     @OA\Property(
 *         property="tournament",
 *         ref="#/components/schemas/Tournament"
 *     ),
 *     @OA\Property(
 *         property="matches",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/TournamentMatch")
 *     ),
 *     @OA\Property(
 *         property="winner",
 *         ref="#/components/schemas/Player"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Error",
 *     type="object",
 *     required={"code","message"},
 *     @OA\Property(property="code", type="integer", example=400),
 *     @OA\Property(property="message", type="string", example="Error description"),
 *     @OA\Property(property="other_info", type="string", example="Other description error")
 * )
 */
class Schemas {}