<?php
namespace Src\Application\UseCases\Tournament;

use Src\Application\DTOs\Tournament\CreateTournamentDTO;

use Src\Domain\Repositories\TournamentRepositoryInterface;
use Src\Domain\Entities\Tournament;
use Src\Domain\Exceptions\ValidationException;

class CreateTournamentUseCase {
    public function __construct(
        private TournamentRepositoryInterface $tournamentRepository
    ) {}

    public function execute(CreateTournamentDTO $dto): Tournament {
        try
        {
           $tournament = new Tournament(
                null, 
                $dto->name, 
                $dto->category
           );
        }
        catch(\InvalidArgumentException $e)
        {
            throw new ValidationException($e->getMessage());
        }

        return $this->tournamentRepository->save($tournament);
    }
}