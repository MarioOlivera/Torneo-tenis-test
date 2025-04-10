<?php
namespace Src\Application\UseCases\Tournament;

use Src\Domain\Entities\Tournament;
use Src\Domain\Repositories\TournamentRepositoryInterface;
use Src\Domain\Exceptions\TournamentNotFoundException;
use Src\Domain\Enums\TournamentStatus;

use Src\Domain\Exceptions\TournamentIsNotPendingException;

class CancelTournamentUseCase {
    public function __construct(
        private TournamentRepositoryInterface $tournamentRepository
    ) {}

    public function execute(int $tournamentId): Tournament {

        $tournament = $this->tournamentRepository->findById($tournamentId);

        if (!$tournament) {
            throw new TournamentNotFoundException("Tournament ".$tournamentId." not found", 404);
        }

        if($tournament->getStatus()->value != TournamentStatus::PENDING->value) {
            throw new TournamentIsNotPendingException("Tournament ".$tournamentId." is not pending", 400);
        }

        $tournament->setStatus(TournamentStatus::CANCELLED);

        return $this->tournamentRepository->save($tournament);
    }
}