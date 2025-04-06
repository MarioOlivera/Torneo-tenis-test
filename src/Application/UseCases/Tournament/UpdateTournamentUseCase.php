<?php
namespace Src\Application\UseCases\Tournament;

use Src\Application\DTOs\Tournament\UpdateTournamentDTO;
use Src\Domain\Repositories\TournamentRepositoryInterface;
use Src\Domain\Entities\Tournament;

class UpdateTournamentUseCase {
    public function __construct(
        private TournamentRepositoryInterface $tournamentRepository
    ) {}

    public function execute(UpdateTournamentDTO $dto): Tournament {
        $tournament = $this->tournamentRepository->findById($dto->id);

        if (!$tournament) {
            throw new \Src\Domain\Exceptions\DomainException(
                "Tournament not found", 
                \Src\Domain\Enums\ErrorCode::VALIDATION, 
                404
            );
        }

        if (!is_null($dto->name)) {
            $tournament->setName($dto->name);
        }

        return $this->tournamentRepository->save($tournament);
    }
}
