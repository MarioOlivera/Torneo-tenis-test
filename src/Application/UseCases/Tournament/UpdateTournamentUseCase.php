<?php
namespace Src\Application\UseCases\Tournament;

use Src\Application\DTOs\Tournament\UpdateTournamentDTO;
use Src\Domain\Repositories\TournamentRepositoryInterface;
use Src\Domain\Entities\Tournament;
use Src\Domain\Exceptions\TournamentNotFoundException;

class UpdateTournamentUseCase {
    public function __construct(
        private TournamentRepositoryInterface $tournamentRepository
    ) {}

    public function execute(UpdateTournamentDTO $dto): Tournament {
        $tournament = $this->tournamentRepository->findById($dto->id);

        if (!$tournament) {
            throw new TournamentNotFoundException("Tournament ".$dto->id." not found", 404);
        }

        if (!is_null($dto->name)) {
            $tournament->setName($dto->name);
        }

        return $this->tournamentRepository->save($tournament);
    }
}
