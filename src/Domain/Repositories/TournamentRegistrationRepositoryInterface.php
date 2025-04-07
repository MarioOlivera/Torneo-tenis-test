<?php
namespace Src\Domain\Repositories;

use Src\Domain\Entities\TournamentRegistration;

interface TournamentRegistrationRepositoryInterface {
    public function save(TournamentRegistration $tournament_registration): TournamentRegistration;
    public function hasTournamentRegistrationWithStatus(int $playerId, int $statusId): bool;
    public function getByPlayerIdAndTournamentId(int $player_id, int $tournament_id): ?TournamentRegistration;
}